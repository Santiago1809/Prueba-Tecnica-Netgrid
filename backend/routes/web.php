<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TareaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
/* Estas dos rutas definen los puntos finales para el registro de usuarios y el inicio de sesión de
usuarios en una API. */
Route::post('/api/v1/user/register', [RegisterController::class, 'store']);
Route::post('/api/v1/user/login', [LoginController::class, 'login']);
/* Estas rutas definen los puntos finales para las operaciones CRUD en proyectos . */
Route::get('api/v1/project/{id}', [ProyectoController::class, 'get']);
Route::get('api/v1/project/only/{id}', [ProyectoController::class, 'getOne']);
Route::post('/api/v1/project/create', [ProyectoController::class, 'create']);
Route::put('api/v1/project/update/{id}', [ProyectoController::class, 'update']);
Route::delete('api/v1/project/{id}', [ProyectoController::class, 'delete']);
/* Estas rutas definen los puntos finales para realizar operaciones CRUD en tareas y proyectos en una
API. */
Route::post('/api/v1/task/create', [TareaController::class, 'create']);
Route::get('api/v1/task/{id}', [TareaController::class, 'get']);
Route::put('api/v1/task/update/{id}', [TareaController::class, 'update']);
Route::delete('api/v1/task/{id}', [TareaController::class, 'delete']);
Route::get('api/v1/task/only/{id}', [TareaController::class, 'getOne']);
/* La ruta `Route::get('/csrf-token', function () { return response()->json(['csrf_token' =>
csrf_token()]); });` se usa para recuperar el token CSRF para la aplicación CSRF (Cross-Site Request
Forgery) es una medida de seguridad utilizada para proteger contra solicitudes no autorizadas. */
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
