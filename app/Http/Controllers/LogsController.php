<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repository\LogsRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;

class LogsController extends Controller
{
    /** @var \Illuminate\Contracts\Auth\Authenticatable|null  */
    private $user;

    /** @var LogsRepository  */
    private $logsRepository;

    public function __construct(LogsRepository $repository, Guard $guard)
    {
        $this->logsRepository = $repository;
        $this->user = $guard->user();
    }

    public function index(): JsonResponse
    {
        if (!$this->user->hasPermission('view-logs')) {
            return $this->noPermissionsResponse();
        }

        return response()->json(['data' => $this->logsRepository->findAll()]);
    }
}