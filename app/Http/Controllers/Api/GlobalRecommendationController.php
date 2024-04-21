<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GlobalRecommendation;

class GlobalRecommendationController extends Controller
{
    /**
     * Get the latest global recommendations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard_lineup_best_team(){
        $recommendations = GlobalRecommendation::with(
            'player.predictions')
            ->orderBy('gameweek','desc')
            ->limit(11)
            ->get();

        if ($recommendations->isEmpty()) {
            return response()->json(['message' => 'No games found for this player'], 404);
        }

        $transformed = $recommendations->map(function ($recommendation) {
            return [
                'id_mundo_deportivo' => $recommendation->id_mundo_deportivo,
                'full_name' => $recommendation->player->full_name,
                'position' => $recommendation->player->position,
                'photo_body' => $recommendation->player->photo_body,
                'photo_face' => $recommendation->player->photo_face,
                'points' => $recommendation->player->predictions[0]->point_prediction,
            ];
        });

        return response()->json($transformed);
    }
}
