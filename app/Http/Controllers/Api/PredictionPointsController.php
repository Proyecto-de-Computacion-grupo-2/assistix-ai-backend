<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PredictionPoints;
use Illuminate\Http\Request;

class PredictionPointsController extends Controller
{
    public function prediction_points_for_a_player_game_week($player_id, $gameweek)
    {
        $player_prediction = PredictionPoints::where('id_mundo_deportivo', $player_id)->where('gameweek', $gameweek)->get();

        if ($player_prediction->isEmpty()) {
            return response()->json(['message' => 'No points prediction found for this player'], 404);
        }
        return $player_prediction;
    }

}
