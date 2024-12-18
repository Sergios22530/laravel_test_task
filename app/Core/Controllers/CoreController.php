<?php

namespace App\Core\Controllers;

use App\Core\Repositories\CoreRepository;
use App\Http\Controllers\Controller;
use App\Logger\Traits\LoggerTrait;

/**
 * Class CoreController
 *
 * @property CoreRepository $repository
 * @package App\Core\Controllers
 */
abstract class CoreController extends Controller
{
    use LoggerTrait;

    protected CoreRepository $repository;

    public function __construct()
    {
    }
}
