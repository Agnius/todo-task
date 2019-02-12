<?php

namespace App\Repository;


use App\Models\Log;

class LogsRepository extends AbstractRepository
{
    public function __construct(Log $log)
    {
        $this->model = $log;
    }
}