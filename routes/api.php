<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Rotas do PacienteController
Route::post('/paciente', [PacienteController::class, 'insert']);
Route::get('/pacientes', [PacienteController::class, 'listActive']);
Route::get('/pacientes/excluidos', [PacienteController::class, 'listDeleted']);
Route::delete('/paciente/{id}', [PacienteController::class, 'delete']);
Route::put('/pacientes/{id}/restaurar', [PacienteController::class, 'restoreById']);
Route::put('/pacientes/cpf/{cpf}/restaurar', [PacienteController::class, 'restoreByCpf']);
