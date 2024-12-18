<?php

namespace App\Core\Controllers;

use App\Core\Formatters\JsonResponseFormatter;
use App\Core\Repositories\CoreRepository;
use App\Http\Controllers\Controller;
use App\Logger\Traits\LoggerTrait;

/**
 * Class CoreApiController
 *
 * @property CoreApiController $repository
 * @property JsonResponseFormatter $formatter
 * @package App\Core\Controllers
 */
abstract class CoreApiController extends Controller
{
    use LoggerTrait;

    protected JsonResponseFormatter $formatter;

    protected CoreRepository $repository;

    public function __construct()
    {
        $this->formatter = new JsonResponseFormatter();
    }
}
