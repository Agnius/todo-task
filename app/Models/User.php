<?php declare(strict_types=1);

namespace App\Models;

use App\Utils\Permissions;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'role_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function isResourceOwner(Model $resource): bool
    {
        return $this->can('access-own-resource', $resource);
    }

    /**
     * @param string $slug
     * @param Model $resource
     * @return bool
     */
    public function hasPermission(string $slug, Model $resource = null): bool
    {
        $permissions = $this->role->permissions->pluck('slug')->all();
        $higherPermission = Permissions::HIGHER_PERMISSIONS[$slug] ?? null;

        // First check if user has higher permission with higher priority
        // If not check if user has lower permission and is resource owner
        foreach ($permissions as $item) {
            if (($item === $higherPermission) || $item === $slug) {
                if (in_array($slug, ['view-own-tasks', 'update-task', 'delete-task']) && !$this->isResourceOwner($resource)) {
                    continue;
                }

                return true;
            }
        }

        return false;

    }
}
