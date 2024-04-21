<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserRecommendation;
use Illuminate\Http\Request;

class UserRecommendationController extends Controller
{
    public function dashboard_lineup_user_market_team($id_user)
    {
        $user_recommendations = UserRecommendation::with('player.predictions')->where('id_user', $id_user)
            ->where('operation_type', '=', 'Titular Mercado')
            ->get();

        $transformed = $user_recommendations->map(function ($recommendation) {
            return [
                'id_mundo_deportivo' => $recommendation->id_mundo_deportivo,
                'full_name' => $recommendation->player->full_name,
                'position' => $recommendation->player->position,
                'photo_face' => $recommendation->player->photo_face,
                'photo_body' => $recommendation->player->photo_body,
                'prediction' => $recommendation->player->predictions[0]->point_prediction,
            ];
        });

        return response()->json($transformed);
    }
}
