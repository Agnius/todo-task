<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\LogActionEvent;
use App\Models\Task;
use App\Repository\TaskRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /** @var TaskRepository $taskRepository */
    private $taskRepository;

    /** @var \Illuminate\Contracts\Auth\Authenticatable|null $user */
    private $user;

    public function __construct(TaskRepository $repository, Guard $guard)
    {
        $this->taskRepository = $repository;
        $this->user = $guard->user();
    }

    /**
     * Find all tasks
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        if (!$this->user->hasPermission('view-others-tasks')) {
            return $this->noPermissionsResponse();
        }

        event(new LogActionEvent(Task::class, 'view-tasks-index', 'Viewed by: '.$this->user->email));

        return response()->json(['data' => $this->taskRepository->findAll()]);
    }

    /**
     * Show task by given id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function view(Request $request): JsonResponse
    {
        if (!$this->user->hasPermission('view-others-tasks')) {
            return $this->noPermissionsResponse();
        }

        event(new LogActionEvent(Task::class, 'view', 'Viewed by: '.$this->user->email));

        return response()->json(['data' => $this->taskRepository->findById((int)$request->route('id'))]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function viewByUser(Request $request): JsonResponse
    {
        if (!$this->user->hasPermission('view-all-tasks')) {
            return $this->noPermissionsResponse();
        }

        event(new LogActionEvent(Task::class, 'view-tasks-by-user', 'Viewed by: '.$this->user->email));

        return response()->json(['data' => $this->taskRepository->findListByUser($request->id)]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        if (!$this->user->hasPermission('create-task')) {
            return $this->noPermissionsResponse();
        }

        $this->validate($request, [
            'description' => 'required|string|max:500',
        ]);

        $ret = $this->taskRepository->add([
            'description' => $request->description,
            'creator_id' => $this->user->id,
        ]);

        event(new LogActionEvent(Task::class, 'create', 'Created by: '.$this->user->email));


        return response()->json(['data' => $ret]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $task = $this->taskRepository->findById((int)$request->route('id'));

        if (!$this->user->hasPermission('update-task', $task)) {
            return $this->noPermissionsResponse();
        }

        $task->description = $request->description;
        $task->save();

        event(new LogActionEvent(Task::class, 'update', 'Updated by: '.$this->user->email));

        return response()->json(['data' => true]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {

        $task = $this->taskRepository->findById((int)$request->route('id'));

        if (!$this->user->hasPermission('delete-task', $task)) {
            return $this->noPermissionsResponse();
        }

        $this->taskRepository->deleteById($task->id);

        event(new LogActionEvent(Task::class, 'delete', 'Deleted by: '.$this->user->email));


        return response()->json([]);
    }

    /**
     * Show currently authenticated user tasks (own)
     *
     * @return JsonResponse
     */
    public function userTasks(): JsonResponse
    {
        event(new LogActionEvent(Task::class, 'view-own-tasks', 'Viewed by: '.$this->user->email));

        return response()->json(['data' => $this->taskRepository->findListByUser($this->user->id)]);
    }
}