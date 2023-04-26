<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
use PharIo\Manifest\AuthorCollection;

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

Route::middleware('auth:sanctum')->get('/user', function () {
    Route::get('/todos', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::put('/todos', [TodoController::class, 'update']);
    Route::delete('/todos', [TodoController::class, 'destroy']);
    Route::post('/todos', [TodoController::class, 'logout']);
});

Route::post('/login', [AuthorCollection::class, 'login']);

Route::get('/version', function () {
    return response()->json([
        'app_version' => '0.0',
        'laravel' => app()::VERSION,
    ]);
});
