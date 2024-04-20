<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;

class PlayerController extends Controller
{
    /**
     * Get all the players in the database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayers()
    {
        $players = Player::all('id_mundo_deportivo', 'full_name', 'position', 'player_value', 'photo_body', 'photo_face', 'season_23_24');
        return response()->json($players);
    }

    /**
     * Get all the information for a specific player.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayer($id)
    {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        if (!$player->leagueUser) {
            return response()->json(['message' => 'Associated league user not found'], 404);
        }

        if (!$player->games) {
            return response()->json(['message' => 'No Games found for player'], 404);
        }

        /**
         * Get the last game the player played, so we can get the team logo.
         */
        $player_games = $player->games->last();

        $response = [
            'id_mundo_deportivo' => $player->id_mundo_deportivo,
            'photo_body' => $player->photo_body,
            'photo_face' => $player->photo_face,
            'full_name' => $player->full_name,
            'position' => $player->position,
            'user_name' => $player->leagueUser->team_name,
            'team' => $player_games->team
        ];
        return response()->json($response);
    }

    /**
     * Get the last point prediction for a player.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayerNextPrediction($id)
    {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        if (!$player->predictions) {
            return response()->json(['message' => 'No predictions found for this player'], 404);
        }

        $prediction = $player->predictions->last();

        $response = [
            'id_mundo_deportivo' => $prediction->id_mundo_deportivo,
            'gameweek' => $prediction->gameweek,
            'date_prediction' => $prediction->date_prediction,
            'point_prediction' => $prediction->point_prediction
        ];

        return response()->json($response);
    }

    /**
     * Get all the absence for a player.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayerAbsences($id) {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json($player->absences);
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
        $players = Player::where('is_in_market', 1)->get(['id_mundo_deportivo', 'full_name', 'position', 'is_in_market', 'sell_price', 'photo_body', 'photo_face']);

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
