<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;


class GameController extends Controller
{
    /**
     * Return the 3 best performing players on the latest game week.
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard_get_three_best_players()
    {
        $get_best_player_in_the_last_gameweek = Game::with(['player:id_mundo_deportivo,full_name,photo_body,photo_face'])
            ->select('id_mundo_deportivo', 'game_week', 'mixed')
            ->orderBy('game_week', 'desc')
            ->orderBy('mixed', 'desc')
            ->limit(3)
            ->get();

        return response()->json(
            $get_best_player_in_the_last_gameweek->map(function ($game) {
                // Ensure that the player data is available
                if ($game->player) {
                    return [
                        'id_mundo_deportivo' => $game->player->id_mundo_deportivo,
                        'full_name' => $game->player->full_name,
                        'photo_body' => $game->player->photo_body,
                        'photo_face' => $game->player->photo_face,
                        'game_week' => $game->game_week,
                        'mixed' => $game->mixed
                    ];
                }
                return null;
            })->filter()->values()
        );
    }
}
