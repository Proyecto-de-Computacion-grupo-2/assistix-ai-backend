<?php

use Illuminate\Support\Facades\Route;

Route::get('/jugadores',[\App\Http\Controllers\JugadorController::class,'getAll']);
Route::get('/jugadores/{id}',[\App\Http\Controllers\JugadorController::class,'get']);
