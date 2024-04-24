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

        $sorted = $players->sortByDesc('season_23_24');

        return response()->json(array_values($sorted->toArray()));
    }

    /**
     * Get all the information for a specific player.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function player_id_get_player_basic_info($id)
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
            'full_name' => $player->full_name,
            'player_value' => $player->player_value,
            'photo_body' => $player->photo_body,
            'photo_face' => $player->photo_face,
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
    public function player_id_get_player_next_prediction($id)
    {
        $player = $this->findPlayerOrFail($id);

        if (!$player->predictions) {
            return response()->json(['message' => 'No predictions found for this player'], 404);
        }

        $prediction = $player->predictions->first();

        $response = [
            'id_mundo_deportivo' => $prediction->id_mundo_deportivo,
            'gameweek' => $prediction->gameweek,
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

        $response = $player->games->map(function ($game) {
            return [
                "game_week" => $game->game_week,
                "team" => $game->team,
                "opposing_team" => $game->opposing_team,
                "mixed" => $game->mixed,
                "as_score" => $game->as_score,
                "marca_score" => $game->marca_score,
                "mundo_deportivo_score" => $game->mundo_deportivo_score,
                "sofa_score" => $game->sofa_score,
                "yellow_card" => $game->yellow_card,
                "double_yellow_card" => $game->double_yellow_card,
                "red_card" => $game->red_card,
                "total_passes" => $game->total_passes,
                "accurate_passes" => $game->accurate_passes,
                "total_long_balls" => $game->total_long_balls,
                "accurate_long_balls" => $game->accurate_long_balls,
                "total_cross" => $game->total_cross,
                "accurate_cross" => $game->accurate_cross,
                "total_clearance" => $game->total_clearance,
                "clearance_off_line" => $game->clearance_off_line,
                "aerial_lost" => $game->aerial_lost,
                "aerial_won" => $game->aerial_won,
                "duel_lost" => $game->duel_lost,
                "duel_won" => $game->duel_won,
                "dispossessed" => $game->dispossessed,
                "challenge_lost" => $game->challenge_lost,
                "total_contest" => $game->total_contest,
                "won_contest" => $game->won_contest,
                "good_high_claim" => $game->good_high_claim,
                "punches" => $game->punches,
                "error_lead_to_a_shot" => $game->error_lead_to_a_shot,
                "error_lead_to_a_goal" => $game->error_lead_to_a_goal,
                "shot_off_target" => $game->shot_off_target,
                "on_target_scoring_attempt" => $game->on_target_scoring_attempt,
                "hit_woodwork" => $game->hit_woodwork,
                "blocked_scoring_attempt" => $game->blocked_scoring_attempt,
                "outfielder_block" => $game->outfielder_block,
                "big_chance_created" => $game->big_chance_created,
                "big_chance_missed" => $game->big_chance_missed,
                "penalty_conceded" => $game->penalty_conceded,
                "penalty_won" => $game->penalty_won,
                "penalty_miss" => $game->penalty_miss,
                "penalty_save" => $game->penalty_save,
                "goals" => $game->goals,
                "own_goals" => $game->own_goals,
                "saved_shots_from_inside_the_box" => $game->saved_shots_from_inside_the_box,
                "saves" => $game->saves,
                "goal_assist" => $game->goal_assist,
                "goals_against" => $game->goals_against,
                "goals_avoided" => $game->goals_avoided,
                "interception_won" => $game->interception_won,
                "total_interceptions" => $game->total_interceptions,
                "total_keeper_sweeper" => $game->total_keeper_sweeper,
                "accurate_keeper_sweeper" => $game->accurate_keeper_sweeper,
                "total_tackle" => $game->total_tackle,
                "was_fouled" => $game->was_fouled,
                "fouls" => $game->fouls,
                "total_offside" => $game->total_offside,
                "minutes_played" => $game->minutes_played,
                "touches" => $game->touches,
                "last_man_tackle" => $game->last_man_tackle,
                "possession_lost_control" => $game->possession_lost_control,
                "expected_goals" => $game->expected_goals,
                "goals_prevented" => $game->goals_prevented,
                "key_pass" => $game->key_pass,
                "expected_assists" => $game->expected_assists
            ];
        });

        return response()->json($response);
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

        return response()->json($predictions);
    }

    /**
     * Get all the past price values for a player.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayerHistoricValueMarket($id)
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
        $players = Player::where('is_in_market', 1)->get(['id_mundo_deportivo', 'full_name', 'player_value', 'position', 'photo_face', 'season_23_24', 'sell_price', 'is_in_market']);

        if ($players->isEmpty()) {
            return response()->json(['message' => 'No players found in the market.'], 404);
        }

        return response()->json($players);
    }

    /**
     * Get all the players in a user team.
     * @param $id_user
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard_lineup_user_team($id_user)
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

        return response()->json(array_values($sorted->toArray()));
    }

    /**
     * Get the point predictions for all the players in the next game week.
     * @return \Illuminate\Http\JsonResponse
     */
    public function playersPointsPredictions()
    {
        $players = Player::with('predictions')
            ->get(['id_mundo_deportivo', 'full_name', 'player_value', 'position', 'photo_face', 'season_23_24']);

        $transformed = $players->map(function ($player) {
            $latestPredictionPoints = $player->predictions->first() ? $player->predictions->first()->point_prediction : null;

            return [
                'id_mundo_deportivo' => $player->id_mundo_deportivo,
                'full_name' => $player->full_name,
                'player_value' => $player->player_value,
                'position' => $player->position,
                'photo_face' => $player->photo_face,
                'season_23_24' => $player->season_23_24,
                'point_prediction' => $latestPredictionPoints
            ];
        });

        $sorted = $transformed->sortByDesc('point_prediction');

        return response()->json(array_values($sorted->toArray()));
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
            'position',
            'photo_face',
            'season_23_24',
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
                'position' => $player->position,
                'photo_face' => $player->photo_face,
                'season_23_24' => $player->season_23_24,
                'latest_prediction' => $player->latest_prediction_price,
                'percentage_change' => $percentageChange
            ];
        });

        $sorted = $transformed->sortByDesc('percentage_change');

        return response()->json(array_values($sorted->toArray()));
    }
}
