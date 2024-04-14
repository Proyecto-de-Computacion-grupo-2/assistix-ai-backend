<?php

use Illuminate\Support\Facades\Route;


/**
 * Absence endpoint.
 */
Route::get('/absences/{id}', [\App\Http\Controllers\Api\AbsenceController::class, 'getAbsences']);

/**
 *  Games endpoint.
 */
Route::get('/games/{id}', [\App\Http\Controllers\Api\GameController::class, 'getGames']);

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
 *  Players endpoint.
 */
Route::get('/players', [\App\Http\Controllers\Api\PlayerController::class, 'index']);
Route::get('/players/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'show']);

/**
 * Prediction points.
 */
Route::get('/player/pp/{id_player}/{gameweek}',[\App\Http\Controllers\Api\PredictionPointsController::class, 'prediction_points_for_a_player_game_week']);

/**
 * Price variation.
 */
Route::get('/player_value/{player_id}', [\App\Http\Controllers\Api\PriceVariationController::class, 'getPlayerValue']);

/**
 * User recommendations.
 */
Route::get('/ur/{id_user}/{gameweek}', [\App\Http\Controllers\Api\UserRecommendationController::class, 'getUserRecommendationGameWeek']);

