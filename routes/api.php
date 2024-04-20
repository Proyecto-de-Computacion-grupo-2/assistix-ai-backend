<?php

use Illuminate\Support\Facades\Route;

/**
 * Global recommendation endpoint.
 */
Route::get('/gr/{gw}', [\App\Http\Controllers\Api\GlobalRecommendationController::class, 'getGlobalRecommendationsGW']);

/**
 * League users endpoint.
 */
Route::get('/users_table', [\App\Http\Controllers\Api\LeagueUserController::class, 'getUsersRankingTable']);
Route::get('/users/{id}', [\App\Http\Controllers\Api\LeagueUserController::class, 'getUserMoneyDetails']);
Route::get('/users/{id}/{email}/{password}', [\App\Http\Controllers\Api\LeagueUserController::class, 'addUserLoginCredentials']); # Change this to post.
Route::get('/users_info/{id}', [\App\Http\Controllers\Api\LeagueUserController::class, 'getUser']);

/**
 * Prediction points.
 */
Route::get('/player/pp/{id_player}/{gameweek}', [\App\Http\Controllers\Api\PredictionPointsController::class, 'prediction_points_for_a_player_game_week']);

/**
 *  --------------------------------------------------------------- Custom Endpoints ---------------------------------------------------------------
 */

/**
 * Dashboard page.
 */
Route::get('/players_user/{id_user}', [\App\Http\Controllers\Api\PlayerController::class, 'playersUser']);
Route::get('/magic', [\App\Http\Controllers\Api\GameController::class, 'getBestThreePlayers']);

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
Route::get('/ur/{id_user}', [\App\Http\Controllers\Api\LeagueUserController::class, 'getRecommendationsLeagueUser']);

