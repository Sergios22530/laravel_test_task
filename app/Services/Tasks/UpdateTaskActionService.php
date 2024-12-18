<?php

namespace App\Services\Tasks;

use App\Repositories\TaskRepository;
use App\Services\BaseActionService;
use Exception;
use Throwable;

/**
 * @property TaskRepository $repository
 */
class UpdateTaskActionService extends BaseActionService
{
    public function __construct()
    {
        $this->repository = app(TaskRepository::class);
    }

    public function handle(): BaseActionService
    {
        try {
            $isUpdated = $this->repository->update($this->model, $this->attributes->getEloquentAttributes());
            if (!$isUpdated) $this->setError('Failed to update task');
        } catch (Exception|Throwable $exception) {
            $this->logError($exception, 'method handle() is down');
            $this->setError($exception->getMessage());
        }

        return $this;
    }
}
