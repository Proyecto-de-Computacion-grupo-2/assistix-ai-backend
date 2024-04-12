<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    /**
     * Get all the players in the database.
     *
     * @return false|string
     */
    public function index()
    {
        $players = Player::all();
        return json_encode($players);
    }

    /**
     * Get all the information for a specific player.
     *
     * @param $id
     * @return false|string
     */
    public function show($id)
    {
        $player = Player::find($id);
        if (!$player) {
            return json_encode(['message' => 'Player not found'], 404);
        }
        return json_encode($player);
    }
}
