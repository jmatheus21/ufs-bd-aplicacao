<?php

use App\Http\Controllers\AeronaveController;
use App\Http\Controllers\MantemController;
use App\Http\Controllers\MecanicoController;
use Illuminate\Support\Facades\Route;

// ROTAS PARA AERONAVE
Route::get('/aeronave', [AeronaveController::class, 'index']);
Route::post('/aeronave', [AeronaveController::class, 'store']);
Route::put('/aeronave/{aeronave}', [AeronaveController::class, 'update']);
Route::delete('/aeronave/{aeronave}', [AeronaveController::class, 'destroy']);

// ROTAS PARA MECANICO
Route::get('/mecanico', [MecanicoController::class, 'index']);
Route::post('/mecanico', [MecanicoController::class, 'store']);
Route::put('/mecanico/{mecanico}', [MecanicoController::class, 'update']);
Route::delete('/mecanico/{mecanico}', [MecanicoController::class, 'destroy']);

// ROTAS PARA MANTEM
Route::get('/mantem', [MantemController::class, 'index']);
Route::post('/mantem', [MantemController::class, 'store']);
Route::put('/mantem/{mantem}', [MantemController::class, 'update']);
Route::delete('/mantem/{mantem}', [MantemController::class, 'destroy']);