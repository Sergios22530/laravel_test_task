<?php

namespace App\Http\Controllers;

use App\Core\Controllers\CoreController;
use App\Repositories\TaskRepository;

/**
 * @property TaskRepository $repository
 */
class TaskController extends CoreController
{
    public function __construct()
    {
        parent::__construct();
        $this->repository = app(TaskRepository::class);
    }

    /**
     * Show the tasks list.
     */
    public function index()
    {

        return view('tasks.index')->with('tasks', $this->repository->getAll());
    }
}
