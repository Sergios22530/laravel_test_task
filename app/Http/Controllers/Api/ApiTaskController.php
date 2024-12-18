<?php

namespace App\Http\Controllers\Api;

use App\Core\Controllers\CoreApiController;
use App\Http\Controllers\Api\Requests\CreateTaskRequest;
use App\Http\Controllers\Api\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskListResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\Tasks\CreateTaskActionService;
use App\Services\Tasks\DeleteTaskActionService;
use App\Services\Tasks\TaskAttribute;
use App\Services\Tasks\UpdateTaskActionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * @property TaskRepository $repository
 */
class ApiTaskController extends CoreApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->repository = app(TaskRepository::class);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request)
    {
        $models = $this->repository->datatableListSubQuery();


        $result = DataTables::eloquent($models)
            ->filter(function ($query) use ($request) {
                $search = $request->input('search.value');

                if ($search) {
                    $query->where(
                        function ($query) use ($search) {
                            return $query
                                ->where('id', 'LIKE', "%{$search}%")
                                ->orWhere('title', 'LIKE', "%{$search}%")
                                ->orWhere('description', 'LIKE', "%{$search}%")
                                ->orWhere('status', 'LIKE', "%{$search}%")
                                ->orWhere('priority', 'LIKE', "%{$search}%")
                                ->orWhere('completed_at', 'LIKE', "%{$search}%");
                        });
                }
            });

        return $result->setTransformer(new TaskListResource)->toJson();
    }

    /**
     * @param CreateTaskRequest $request
     * @return JsonResponse
     */
    public function create(CreateTaskRequest $request): JsonResponse
    {
        $actionService = (new CreateTaskActionService())
            ->setAttributes(
                (new TaskAttribute())->setRequest($request)->assignAttributes()
            )->handle();

        return response()->json(
            $this->formatter
                ->addData(!$actionService->hasErrors(), 'status')
                ->formatAnswer()
        );
    }

    /**
     * @param Task $task
     * @return TaskResource
     */
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    /**
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $actionService = (new UpdateTaskActionService())
            ->setAttributes(
                (new TaskAttribute())->setRequest($request)->assignAttributes()
            )
            ->setModel($task)
            ->handle();

        return response()->json(
            $this->formatter
                ->addData(!$actionService->hasErrors(), 'status')
                ->formatAnswer()
        );
    }


    /**
     * @param Task $task
     * @return JsonResponse
     */
    public function delete(Task $task): JsonResponse
    {
        $actionService = (new DeleteTaskActionService())
            ->setModel($task)
            ->handle();

        return response()->json(
            $this->formatter
                ->addData(!$actionService->hasErrors(), 'status')
                ->formatAnswer()
        );
    }
}
