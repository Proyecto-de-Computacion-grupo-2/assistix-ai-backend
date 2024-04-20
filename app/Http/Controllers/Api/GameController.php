<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
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
