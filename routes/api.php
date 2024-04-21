<?php

use Illuminate\Support\Facades\Route;

/**
 * Dashboard page.
 */

Route::get('/player/pp/{id_player}/{gameweek}', [\App\Http\Controllers\Api\PredictionPointsController::class, 'prediction_points_for_a_player_game_week']);

# Points predictions component.
Route::get('/players_prediction/value', [\App\Http\Controllers\Api\PlayerController::class, 'playersValuePredictions']);

# Value predictions component.
Route::get('/players_prediction/points', [\App\Http\Controllers\Api\PlayerController::class, 'playersPointsPredictions']);

# Stats component.
Route::get('/players_best', [\App\Http\Controllers\Api\GameController::class, 'getBestThreePlayers']);

# Line up component.
Route::get('/players_user/{id_user}', [\App\Http\Controllers\Api\PlayerController::class, 'playersUser']);
# todo get the max.puntos lineup.
Route::get('/global_recommendation', [\App\Http\Controllers\Api\GlobalRecommendationController::class, 'getGlobalRecommendationsGW']);


/**
 * Market page.
 */
Route::get('/players_market', [\App\Http\Controllers\Api\PlayerController::class, 'playerInMarket']);

/**
 *  Players page.
 */
Route::get('/players', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayers']);

/**
 * Player-id page.
 */
Route::get('/players/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayer']);
Route::get('/players/next_prediction/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayerNextPrediction']);
Route::get('/players/absence/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayerAbsences']);
Route::get('/players/games/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayerGames']);
Route::get('/players/point_predictions/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayerPointsPredictions']);

/**
 * Shared endpoints between pages.
 */
Route::get('/players/value/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayerHistoricValue']);
Route::get('/user_recommendation/{id_user}', [\App\Http\Controllers\Api\LeagueUserController::class, 'getRecommendationsLeagueUser']); # todo FINISH THIS ENDPOINT. "RECOMENDACIONES ASSISTIX AI"

/**
 * League users endpoint.
 */
Route::get('/users_table', [\App\Http\Controllers\Api\LeagueUserController::class, 'getUsersRankingTable']);
Route::get('/users/{id}', [\App\Http\Controllers\Api\LeagueUserController::class, 'getUserMoneyDetails']);
Route::post('/users/{id}/{email}/{password}', [\App\Http\Controllers\Api\LeagueUserController::class, 'addUserLoginCredentials']);
