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

Route::get('/pacientes', [PacienteController::class, 'listar']);
Route::post('/paciente', [PacienteController::class, 'insert']);
// Route::get('paciente', [PacienteController::class, 'dados']);
// Route::put('paciente', [PacienteController::class, 'atualizar']);
// Route::delete('paciente', [PacienteController::class, 'destroy']);