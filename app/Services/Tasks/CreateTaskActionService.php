<?php

namespace App\Services\Tasks;

use App\Repositories\TaskRepository;
use App\Services\BaseActionService;
use Exception;
use Throwable;

/**
 * @property TaskRepository $repository
 */
class CreateTaskActionService extends BaseActionService
{
    public function __construct()
    {
        $this->repository = app(TaskRepository::class);
    }

    public function handle(): BaseActionService
    {
        try {
            $this->repository->store($this->attributes->getEloquentAttributes());
        } catch (Exception|Throwable $exception) {
            $this->logError($exception, 'method handle() is down');
            $this->setError($exception->getMessage());
        }

        return $this;
    }
}
