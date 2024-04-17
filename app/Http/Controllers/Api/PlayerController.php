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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $players = Player::all();
        return response()->json($players);
    }

    /**
     * Get all the information for a specific player.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $player = Player::find($id);
        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }
        return response()->json($player);
    }

    /**
     * Get all the players that are currently in the market.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function playerInMarket()
    {
        $players = Player::where('is_in_market', 1)->get();
        if (!$players) {
            return response()->json(['message' => 'Found no players in the market.'], 404);
        }
        return response()->json($players);
    }

    /**
     * Get all the players in a user team.
     * @param $id_user
     * @return \Illuminate\Http\JsonResponse
     */
    public function playersUser($id_user)
    {
        $players = Player::where('id_user', $id_user)->get();
        if (!$players) {
            return response()->json(['message' => 'Found no players in the market.'], 404);
        }
        return response()->json($players);
    }
}
