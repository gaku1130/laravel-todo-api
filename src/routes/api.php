<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;



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

Route::prefix('v1')->group(function () {
	Route::controller(AuthController::class)->group(function () {
		Route::post('auth/register', 'register');
	});

	Route::controller(AuthController::class)->group(function () {
		Route::post('auth/login', 'login');
	});

	Route::controller(AuthController::class)->group(function () {
		Route::middleware('auth:sanctum')->post('auth/logout', 'logout');
	});

	Route::controller(UserController::class)->group(function () {
		Route::middleware('auth:sanctum')->get('users', 'index');
		Route::middleware(['auth:sanctum', 'check.userId'])->get('users/{userId}', 'show');
	});

    Route::controller(TodoController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'check.userId'])->get('users/{userId}/todos', 'index');
        Route::middleware(['auth:sanctum', 'check.userId'])->post('users/{userId}/todos', 'create');

        Route::middleware([
            'auth:sanctum',
            'check.userId',
            'find.todo',
            'check.todoId',
        ])->get('users/{userId}/todos/{todoId}', 'show');

        Route::middleware([
            'auth:sanctum',
            'check.userId',
            'find.todo',
            'check.todoId',
        ])->put('users/{userId}/todos/{todoId}', 'update');

        Route::middleware([
            'auth:sanctum',
            'check.userId',
            'find.todo',
            'check.todoId',
        ])->delete('users/{userId}/todos/{todoId}', 'destroy');
    });
});