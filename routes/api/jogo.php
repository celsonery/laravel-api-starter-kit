<?php

use App\Http\Controllers\JogoController;
use App\Http\Controllers\SorteioController;
use Illuminate\Support\Facades\Route;

Route::apiResource('jogo', JogoController::class)
    ->except(['show', 'update'])
    ->middleware(['auth:sanctum']);

// Route::get('jogo/resultado/{id}', [JogoController::class, 'result']);
// Route::get('jogo/result', [JogoController::class, 'testdb']);

Route::get('jogo/count', [SorteioController::class, 'index']);
