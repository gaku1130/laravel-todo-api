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

Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::middleware('auth:sanctum')->post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');


Route::middleware('auth:sanctum')->get('/users/{userId}', [UserController::class, 'findUser'])->name('user.find');


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
    ])->delete('users/{userId}/todos/{todoId}', 'delete');
});