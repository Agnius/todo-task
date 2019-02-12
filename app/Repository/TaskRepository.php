<?php declare(strict_types=1);

namespace App\Repository;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TodoRepository
 * @package App\Repository
 */
class TaskRepository extends AbstractRepository
{
    public function __construct(Task $todo)
    {
        $this->model = $todo;
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function findListByUser(int $id): Collection
    {
        return $this->model
            ->where('creator_id', $id)
            ->get();
    }
}