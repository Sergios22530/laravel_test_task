<?php

namespace App\Core\Repositories\Interfaces;

/**
 * Interface ResourceRepositoryInterface for resource controllers
 *
 * @package App\Core\Repositories\Interfaces
 */
interface ResourceRepositoryInterface
{
    public function getAll();

    public function getById($id);

    public function store(array $attributes);

    public function update($model, array $attributes);

    public function delete($model);
}
