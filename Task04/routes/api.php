<?php

use App\Http\Controllers\Api\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{game}', [GameController::class, 'show']);
Route::post('/games', [GameController::class, 'store']);
Route::post('/step/{game}', [GameController::class, 'step']);
