<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    /**
     *
     * Get all the games played by a player.
     *
     * @param $id
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
}
