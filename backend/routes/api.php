<?php

use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\UserController;
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

Route::get('proyectos/{id}', [ProyectoController::class, 'index']);
Route::get('proyectos/proyecto/{id}', [ProyectoController::class, 'show']);
Route::post('proyectos', [ProyectoController::class, 'store']);
Route::put('proyectos/{id}', [ProyectoController::class, 'update']);
Route::delete('proyectos/{id}', [ProyectoController::class, 'destroy']);
Route::get('tareas/{id}', [TareaController::class,'index']);
Route::get('tareas/tarea/{id}', [TareaController::class,'show']);
Route::post('tareas', [TareaController::class,'store']);
Route::put('tareas/{id}', [TareaController::class, 'update']);
Route::delete('tareas/{id}', [TareaController::class, 'destroy']);
Route::post('users', [UserController::class, 'login']);
Route::post('users/register', [UserController::class, 'store']);

