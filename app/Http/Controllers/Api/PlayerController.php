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
     * Retrieves all players currently marked as 'in the market' and their latest four game entries.
     *
     * Each player's information includes selected details such as ID, name, position, and pricing,
     * along with a photo. Additionally, for each player, the function fetches the latest four games,
     * focusing only on the 'game_week' and 'mixed' columns for those games.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with each player's information.
     * If no players are found in the market, a 404 response is returned with an error message.
     */
    public function playerInMarket()
    {
        $players = Player::where('is_in_market', 1)->get(['id_mundo_deportivo','full_name','position','is_in_market','sell_price','photo_body','photo_face']);

        if ($players->isEmpty()) {
            return response()->json(['message' => 'No players found in the market.'], 404);
        }

        // Manually attach the latest four games to each player
        foreach ($players as $player) {
            $latestGames = $player->games()
                ->orderByDesc('game_week')
                ->limit(4)
                ->get(['game_week', 'mixed']);
            $player->latest_games = $latestGames;
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
