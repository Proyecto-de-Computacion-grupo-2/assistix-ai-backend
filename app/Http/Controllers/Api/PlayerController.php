<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    /**
     * Aux function, to check if a player exists or not.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    private function findPlayerOrFail($id)
    {
        $player = Player::find($id);
        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }
        return $player;
    }

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
        $player = $this->findPlayerOrFail($id);

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
        $player = $this->findPlayerOrFail($id);

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
    public function getPlayerAbsences($id)
    {
        $player = $this->findPlayerOrFail($id);

        return response()->json($player->absences);
    }

    /**
     * Get all the games player by a player.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayerGames($id)
    {
        $player = $this->findPlayerOrFail($id);

        if (!$player->games) {
            return response()->json(['message' => 'No Games found for player'], 404);
        }

        return response()->json($player->games);
    }

    /**
     * Get the three latest points predictions for a player.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayerPointsPredictions($id)
    {
        $player = $this->findPlayerOrFail($id);

        $games = $player->predictions()
            ->select('gameweek', 'point_prediction')
            ->orderBy('gameweek', 'desc')
            ->limit(3)
            ->get();

        return response()->json($games);
    }

    /**
     * Get all the past price values for a player.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayerHistoricValue($id)
    {
        $player = $this->findPlayerOrFail($id);

        $predictions = $player->price_variations()
            ->select('price_day', 'price', 'is_prediction')
            ->get();

        $response = [
            'id_mundo_deportivo' => $player->id_mundo_deportivo,
            'full_name' => $player->full_name,
            'position' => $player->position,
            'photo_face' => $player->photo_face,
            'predictions' => $predictions
        ];

        return response()->json($response);
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
        $players = Player::where('is_in_market', 1)->get(['id_mundo_deportivo', 'full_name', 'position', 'sell_price', 'photo_body', 'photo_face']);

        if ($players->isEmpty()) {
            return response()->json(['message' => 'No players found in the market.'], 404);
        }

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
        $players = Player::where('id_user', $id_user)->get(['id_mundo_deportivo', 'full_name', 'position', 'photo_body', 'photo_face']);

        if (!$players) {
            return response()->json(['message' => 'Found no players in the market.'], 404);
        }

        foreach ($players as $player) {
            $latestGame = $player->games()
                ->orderByDesc('game_week')
                ->limit(1)
                ->get(['mixed']);
            $player->latest_game = $latestGame[0]['mixed'];
        }

        $sorted = $players->sortBy('position');


        return response()->json($sorted);
    }

    /**
     * Get the point predictions for all the players in the next game week.
     * @return \Illuminate\Http\JsonResponse
     */
    public function playersPointsPredictions()
    {
        $players = Player::with('predictions')
            ->get(['id_mundo_deportivo', 'full_name', 'position', 'photo_body', 'photo_face']);

        $transformed = $players->map(function ($player) {
            $latestPredictionPoints = $player->predictions->first() ? $player->predictions->first()->point_prediction : null;

            return [
                'id_mundo_deportivo' => $player->id_mundo_deportivo,
                'full_name' => $player->full_name,
                'position' => $player->position,
                'photo_body' => $player->photo_body,
                'photo_face' => $player->photo_face,
                'prediction' => $latestPredictionPoints
            ];
        });
        return response()->json($transformed);
    }

    /**
     * Get the players percentage change between the latest prediction and the latest real price.
     * @return \Illuminate\Http\JsonResponse
     */
    public function playersValuePredictions()
    {
        $players = Player::select([
            'id_mundo_deportivo',
            'full_name',
            'player_value',
            'photo_face',
            DB::raw("(SELECT price FROM price_variation WHERE price_variation.id_mundo_deportivo = player.id_mundo_deportivo AND is_prediction = true ORDER BY price_day DESC LIMIT 1) as latest_prediction_price"),
            DB::raw("(SELECT price FROM price_variation WHERE price_variation.id_mundo_deportivo = player.id_mundo_deportivo AND is_prediction = false ORDER BY price_day DESC LIMIT 1) as latest_real_price"),
        ])->get();

        $transformed = $players->map(function ($player) {
            $priceDifference = $player->latest_prediction_price - $player->latest_real_price;
            $percentageChange = intval(($priceDifference / $player->latest_real_price) * 100);
            return [
                'id_mundo_deportivo' => $player->id_mundo_deportivo,
                'full_name' => $player->full_name,
                'player_value' => $player->player_value,
                'photo_face' => $player->photo_face,
                'latest_prediction' => $player->latest_prediction_price,
                'percentage_change' => $percentageChange
            ];
        });

        $sorted = $transformed->sortByDesc('percentage_change');

        return response()->json(array_values($sorted->toArray()));
    }
}
