<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GlobalRecommendation;
use App\Models\UserRecommendation;
use Illuminate\Http\Request;

class UserRecommendationController extends Controller
{
    public function dashboard_lineup($id_user)
    {
        $teamLineup = $this->get_user_recommendations($id_user, 'Titular', 'team-lineup');
        $marketLineup = $this->get_user_recommendations($id_user, 'Titular Mercado', 'market-lineup');
        $fantasyLineup = $this->get_fantasy_team('fantasy-lineup');

        $response = [
            $teamLineup,
            $marketLineup,
            $fantasyLineup,
        ];

        return response()->json($response);
    }

    private function get_user_recommendations($id_user, $operation_type, $lineup_type)
    {
        $user_recommendations = UserRecommendation::with('player.predictions')
            ->where('id_user', $id_user)
            ->where('operation_type', '=', $operation_type)
            ->get();

        return $this->formatResponseByPosition($user_recommendations, $lineup_type);
    }

    private function get_fantasy_team($lineup_type)
    {
        $recommendations = GlobalRecommendation::with(
            'player.predictions'
        )
            ->orderBy('gameweek', 'desc')
            ->limit(11)
            ->get();

        if ($recommendations->isEmpty()) {
            return response()->json(['message' => 'No games found for this player'], 404);
        }

        return $this->formatResponseByPosition($recommendations, $lineup_type);
    }

    private function formatResponseByPosition($recommendations, $lineup_type)
    {
        $grouped_recommendations = [
            'type' => $lineup_type,
            'goalkeeper' => [],
            'defense' => [],
            'midfield' => [],
            'attack' => [],
        ];

        foreach ($recommendations as $recommendation) {
            $transformed_recommendation = $this->format_response($recommendation);
            switch ($recommendation->player->position) {
                case 1:
                    $grouped_recommendations['goalkeeper'] = $transformed_recommendation;
                    break;
                case 2:
                    $grouped_recommendations['defense'][] = $transformed_recommendation;
                    break;
                case 3:
                    $grouped_recommendations['midfield'][] = $transformed_recommendation;
                    break;
                case 4:
                    $grouped_recommendations['attack'][] = $transformed_recommendation;
                    break;
            }
        }

        return $grouped_recommendations;
    }

    private function format_response($recommendation)
    {
        return [
            'id_mundo_deportivo' => $recommendation->id_mundo_deportivo,
            'full_name' => $recommendation->player->full_name,
            'position' => $recommendation->player->position,
            'photo_face' => $recommendation->player->photo_face,
            'photo_body' => $recommendation->player->photo_body,
            'prediction' => $recommendation->player->predictions[0]->point_prediction,
            'player_value' => $recommendation->player->player_value
        ];
    }


}
