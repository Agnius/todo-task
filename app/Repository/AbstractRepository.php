<?php declare(strict_types=1);

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository implements RepositoryInterface
{
    /** @var Model $model */
    protected $model;

    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function add(array $attributes): bool
    {
        $this->model->fill($attributes);

        return $this->model->save();
    }

    public function deleteById(int $id)
    {
        $this->model->where('id', $id)->delete();
    }
}