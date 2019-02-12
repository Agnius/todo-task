<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function noPermissionsResponse(): JsonResponse
    {
        return response()->json([], 403);
    }
}
