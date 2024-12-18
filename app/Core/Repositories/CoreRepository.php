<?php

namespace App\Core\Repositories;

use App\Logger\Traits\LoggerTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CoreRepository
 *
 * Репозиторий работы с екземплярами сущностей.
 * Может выдавать, создавать, изменять наборы данных.
 *
 * @property Model $model
 * @property bool $_withTrashed
 *
 * @package App\Core\Repositories
 *
 */
abstract class CoreRepository
{
    use LoggerTrait;

    public static bool $_withTrashed = false;

    /** @var Model */
    protected $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * @return mixed
     */
    abstract protected function getModelClass();

    /**
     * @return Model
     */
    protected function startConditions()
    {
        return clone $this->model;
    }

    protected function withTrashed(Model &$query)
    {
        if (self::$_withTrashed) $query = $query->withTrashed();
    }
}
