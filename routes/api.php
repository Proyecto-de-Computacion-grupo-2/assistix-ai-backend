<?php

use Illuminate\Support\Facades\Route;

Route::get('/players', [\App\Http\Controllers\Api\PlayerController::class, 'index']);
Route::get('/players/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'show']);
Route::get('/absences/{id}', [\App\Http\Controllers\Api\AbsenceController::class, 'getAbsences']);
Route::get('/games/{id}', [\App\Http\Controllers\Api\GameController::class, 'getGames']);
Route::get('/users/table', [\App\Http\Controllers\Api\LeagueUserController::class, 'getUsersRankingTable']);
Route::get('/users/{id}', [\App\Http\Controllers\Api\LeagueUserController::class, 'getUserMoneyDetails']);
Route::get('/usersAll/{id}', [\App\Http\Controllers\Api\LeagueUserController::class, 'getUser']);
Route::get('users/{id}/{email}/{password}',[\App\Http\Controllers\Api\LeagueUserController::class, 'addUserLoginCredentials']); # Change this to post.
Route::get('gr/{gw}',[\App\Http\Controllers\Api\GlobalRecommendationController::class, 'getGlobalRecommendationsGW']); # Change this to post.

