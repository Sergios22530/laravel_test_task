<?php

use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Api\ApiTaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

$globalPrefix = 'api';

Route::post('login', [ApiLoginController::class, 'login'])->name('api-login');

Route::middleware(['auth:sanctum'])->group(function () use ($globalPrefix) {
    $taskController = ApiTaskController::class;
    Route::group(['prefix' => 'tasks'], function () use ($taskController, $globalPrefix) {
        Route::get('/', [$taskController, 'list'])->name('list-task');
        Route::get('/{task}', [$taskController, 'show'])->name('show-task');
        Route::post('/', [$taskController, 'create'])->name('create-task');
        Route::put('/{task}', [$taskController, 'update'])->name('update-task');
        Route::delete('/{task}', [$taskController, 'delete'])->name('delete-task');
    });
});
