<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;
use Illuminate\Support\Facades\Route;

/**
 * ------- Layout page. -------
 */
Route::middleware(['auth:api', IsUser::class])->get('/users/{id}', [\App\Http\Controllers\Api\LeagueUserController::class, 'layout_get_user_details']);

/**
 * ------- Dashboard page. -------
 */
# Points predictions component.
Route::middleware(['auth:api', IsUser::class])->get('/players_prediction/value', [\App\Http\Controllers\Api\PlayerController::class, 'dashboard_players_value_predictions']);

# Value predictions component.
Route::middleware(['auth:api', IsUser::class])->get('/players_prediction/points', [\App\Http\Controllers\Api\PlayerController::class, 'dashboard_players_points_predictions']);

# Stats component.
Route::middleware(['auth:api', IsUser::class])->get('/players_best', [\App\Http\Controllers\Api\GameController::class, 'dashboard_get_three_best_players']);

# Line up component.
Route::middleware(['auth:api', IsUser::class])->get('/players_user/{id_user}', [\App\Http\Controllers\Api\PlayerController::class, 'dashboard_lineup_user_team']);
Route::middleware(['auth:api', IsUser::class])->get('/user_recommendation/lineup/{id_user}', [\App\Http\Controllers\Api\UserRecommendationController::class, 'dashboard_lineup']);

# Standing component.
Route::middleware(['auth:api', IsUser::class])->get('/users_table', [\App\Http\Controllers\Api\LeagueUserController::class, 'dashboard_get_users_ranking_table']);

/**
 * Market page.
 */
Route::middleware(['auth:api', IsUser::class])->get('/players/market/value/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'market_player_historic_value']);
Route::middleware(['auth:api', IsUser::class])->get('/players_market', [\App\Http\Controllers\Api\PlayerController::class, 'market_players']);

/**
 *  Players page.
 */
Route::middleware(['auth:api', IsUser::class])->get('/players', [\App\Http\Controllers\Api\PlayerController::class, 'players_get_all_players']);

/**
 * Player-id page.
 */
Route::middleware(['auth:api', IsUser::class])->get('/players/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'player_id_get_player_basic_info']);
Route::middleware(['auth:api', IsUser::class])->get('/players/next_prediction/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'player_id_get_player_next_prediction']);
Route::middleware(['auth:api', IsUser::class])->get('/players/absence/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'player_id_get_player_absences']);
Route::middleware(['auth:api', IsUser::class])->get('/players/streak/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'player_id_get_player_streak']);
Route::middleware(['auth:api', IsUser::class])->get('/players/games/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'player_id_get_player_games']);
Route::middleware(['auth:api', IsUser::class])->get('/players/value/{id}', [\App\Http\Controllers\Api\PlayerController::class, 'player_id_get_player_historic_values']);

/**
 * Shared endpoints between pages.
 */
Route::middleware(['auth:api', IsUser::class])->get('/user_recommendation/{id_user}', [\App\Http\Controllers\Api\LeagueUserController::class, 'get_recommendations_league_user']);

/**
 * Admin page.
 */
Route::middleware(['auth:api', IsAdmin::class])->get('/admin', [\App\Http\Controllers\Api\LeagueUserController::class, 'admin_get_users_info']);
Route::middleware(['auth:api', IsAdmin::class])->get('/admin/{id}/{active}', [\App\Http\Controllers\Api\LeagueUserController::class, 'admin_activate_User']);

/**
 * Public endpoint.
 */
Route::post('/users/{id}/{email}/{password}', [\App\Http\Controllers\Api\LeagueUserController::class, 'add_user_login_credentials']);
Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/auth/register', [\App\Http\Controllers\AuthController::class, 'register']);


