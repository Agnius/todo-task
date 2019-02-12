<?php

namespace App\Utils;


class Permissions
{
    // Todo: get database level
    const HIGHER_PERMISSIONS = [
        'update-task' => 'update-others-tasks',
        'delete-task' => 'delete-others-tasks',
        'view-own-tasks' => 'view-others-tasks',
    ];
}