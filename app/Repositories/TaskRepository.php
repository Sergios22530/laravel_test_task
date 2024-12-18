<?php

namespace App\Repositories;

use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Models\QueryBuilders\TaskQueryBuilder;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskRepository extends CoreRepository implements ResourceRepositoryInterface
{
    protected function getModelClass()
    {
        return Task::class;
    }

    /**
     * @return Collection
     */
    public function getAll(): ?Collection
    {
        return $this->startConditions()::byUser()->orderByDesc('id')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->startConditions()->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @return Task
     */
    public function store(array $attributes): Task
    {
        return $this->startConditions()::create($attributes);
    }


    /**
     * @param $model
     * @param array $attributes
     * @return bool
     */
    public function update($model, array $attributes): bool
    {
        return $model->update($attributes);
    }

    /**
     * @param $model
     * @return ?bool
     */
    public function delete($model): ?bool
    {
        return $model->delete();
    }

}
