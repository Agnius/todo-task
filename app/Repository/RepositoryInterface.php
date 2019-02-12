<?php declare(strict_types=1);

namespace App\Repository;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function findById(int $id): ?Model;

    public function findAll(): Collection;
}