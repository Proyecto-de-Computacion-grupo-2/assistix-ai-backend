<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GlobalRecommendation;
use App\Models\UserRecommendation;
use Illuminate\Http\Request;

class UserRecommendationController extends Controller
{
    public function dashboard_lineup_user_market_team($id_user)
    {
        $teamLineup = $this->getUserRecommendations($id_user, 'Titular');
        $marketLineup = $this->getUserRecommendations($id_user, 'Titular Mercado');
        $fantasyLineup = $this->dashboard_lineup_best_team();

        $response = [
            'team-lineup' => $teamLineup,
            'market-lineup' => $marketLineup,
            'fantasy-lineup' => $fantasyLineup,
        ];

        return response()->json($response);
    }

    private function getUserRecommendations($id_user, $operation_type)
    {
        $user_recommendations = UserRecommendation::with('player.predictions')
            ->where('id_user', $id_user)
            ->where('operation_type', '=', $operation_type)
            ->get();

        return $user_recommendations->map(function ($recommendation) {
            return $this->transformRecommendation($recommendation);
        });
    }

    private function dashboard_lineup_best_team(){
        $recommendations = GlobalRecommendation::with(
            'player.predictions')
            ->orderBy('gameweek','desc')
            ->limit(11)
            ->get();

        if ($recommendations->isEmpty()) {
            return response()->json(['message' => 'No games found for this player'], 404);
        }

        return $recommendations->map(function ($recommendation) {
            return $this->transformRecommendation($recommendation);
        });
    }

    private function transformRecommendation($recommendation)
    {
        return [
            'id_mundo_deportivo' => $recommendation->id_mundo_deportivo,
            'full_name' => $recommendation->player->full_name,
            'position' => $recommendation->player->position,
            'photo_face' => $recommendation->player->photo_face,
            'photo_body' => $recommendation->player->photo_body,
            'prediction' => $recommendation->player->predictions[0]->point_prediction,
        ];
    }

}
