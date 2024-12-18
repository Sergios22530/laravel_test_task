<?php

namespace App\Services;

use App\Core\Repositories\CoreRepository;
use App\Core\Traits\ErrorsTrait;
use App\Logger\Traits\LoggerTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property CoreRepository $repository
 * @property BaseAttribute $attributes
 * @property Model $model
 */
abstract class BaseActionService
{
    use LoggerTrait, ErrorsTrait;

    protected CoreRepository $repository;
    protected BaseAttribute $attributes;
    protected Model $model;

    abstract public function handle(): self;

    public function setAttributes(BaseAttribute $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }
}
