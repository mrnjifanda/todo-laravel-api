<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiTodoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'v1/todo'
], function ($router) {

    Route::get('lists', [ApiTodoController::class, 'lists'])->name('api.v1.todo.lists');
    Route::post('create', [ApiTodoController::class, 'create'])->name('api.v1.todo.create');
    Route::post('completed', [ApiTodoController::class, 'completed'])->name('api.v1.todo.completed');
    Route::post('delete', [ApiTodoController::class, 'delete'])->name('api.v1.todo.delete');
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
