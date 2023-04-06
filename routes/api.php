<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PacienteAtendimentoController;

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

Route::get('/paciente/{id}', [PacienteController::class, 'getByID']);
Route::get('/paciente/cpf/{cpf}', [PacienteController::class, 'getByCPF']);

Route::get('/pacientes/excluidos', [PacienteController::class, 'listDeleted']);
Route::get('/pacientes', [PacienteController::class, 'listActive']);

Route::delete('/paciente/{id}', [PacienteController::class, 'delete']);
Route::put('/paciente/{id}/restaurar', [PacienteController::class, 'restoreById']);
Route::put('/paciente/cpf/{cpf}/restaurar', [PacienteController::class, 'restoreById']);

// restoreByCpf
Route::delete('/paciente/{id}/destruir', [PacienteController::class, 'destroy']);
// Route::delete('/paciente/{id}', [PacienteController::class, 'delete']);


// Rotas para atendimentos
Route::get('/atendimentos/{page?}/{limitPerPage?}', [PacienteAtendimentoController::class, 'index']);

Route::post('/paciente/{paciente_id}/atendimento', [PacienteAtendimentoController::class, 'store']);
Route::put('/pacientes/{id}/atendimentos/{atendimento}', [PacienteAtendimentoController::class, 'update']);
