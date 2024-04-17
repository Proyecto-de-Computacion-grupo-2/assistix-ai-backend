<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    /**
     *
     * Get all the games played by a player.
     *
     * @param $id mundo deportivo player id.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGames($id)
    {
        $games = Game::where('id_mundo_deportivo', $id)->get();
        if ($games->isEmpty()) {
            return response()->json(['message' => 'No games found for this player'], 404);
        }
        return response()->json($games);
    }

    /**
     * TODO CONITNUE WIT HTHIS FUNCTION, MAKE IT WORK AFTER THE JOIN.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMagic3Players()
    {
        $players = Game::join('player', 'game.id_mundo_deportivo', '=', 'player.id_mundo_deportivo')
            ->limit(10)  // Limit to 10 rows
            ->get();

        return response()->json($players);
    }
}
