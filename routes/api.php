<?php

use Illuminate\Support\Facades\Route;

/**
 * ------- Dashboard page. -------
 */
//Route::get('/player/pp/{id_player}/{gameweek}', [\App\Http\Controllers\Api\PredictionPointsController::class, 'prediction_points_for_a_player_game_week']); NOT USED FOR THE MOMENT

# Points predictions component.
Route::get('/players_prediction/value', [\App\Http\Controllers\Api\PlayerController::class, 'playersValuePredictions']);

# Value predictions component.
Route::get('/players_prediction/points', [\App\Http\Controllers\Api\PlayerController::class, 'playersPointsPredictions']);

# Stats component.
Route::get('/players_best', [\App\Http\Controllers\Api\GameController::class, 'dashboard_get_three_best_players']);

# Line up component.
Route::get('/players_user/{id_user}', [\App\Http\Controllers\Api\PlayerController::class, 'dashboard_lineup_user_team']);
Route::get('/user_recommendation/lineup/{id_user}', [\App\Http\Controllers\Api\UserRecommendationController::class, 'dashboard_lineup']);

/**
 * Market page.
 */
Route::get('/players/market/value/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayerHistoricValueMarket']);
Route::get('/players_market', [\App\Http\Controllers\Api\PlayerController::class, 'playerInMarket']);

/**
 *  Players page.
 */
Route::get('/players', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayers']);

/**
 * Player-id page.
 */
Route::get('/players/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'player_id_get_player_basic_info']);
Route::get('/players/next_prediction/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'player_id_get_player_next_prediction']);
Route::get('/players/absence/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayerAbsences']);
Route::get('/players/games/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayerGames']);
Route::get('/players/point_predictions/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayerPointsPredictions']);
Route::get('/players/value/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'getPlayerHistoricValue']);

/**
 * Shared endpoints between pages.
 */
Route::get('/user_recommendation/{id_user}', [\App\Http\Controllers\Api\LeagueUserController::class, 'getRecommendationsLeagueUser']);

/**
 * League users endpoint.
 */
Route::get('/users_table', [\App\Http\Controllers\Api\LeagueUserController::class, 'get_users_ranking_table']);
Route::get('/users/{id}', [\App\Http\Controllers\Api\LeagueUserController::class, 'getUserMoneyDetails']);
Route::post('/users/{id}/{email}/{password}', [\App\Http\Controllers\Api\LeagueUserController::class, 'addUserLoginCredentials']);
Route::get('/admin', [\App\Http\Controllers\Api\LeagueUserController::class, 'getUserAdminInfo']);
Route::get('/admin/{id}/{active}', [\App\Http\Controllers\Api\LeagueUserController::class, 'activateUser']);
Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
