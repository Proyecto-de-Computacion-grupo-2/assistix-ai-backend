<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserRecommendation;
use Illuminate\Http\Request;

class UserRecommendationController extends Controller
{
    public function getUserRecommendationGameWeek($id_user,$gameweek)
    {
        $user_recommendations = UserRecommendation::where('id_user', $id_user)->where('gameweek',$gameweek)->get();
        if ($user_recommendations->isEmpty()) {
            return response()->json(['message' => 'No recommendations found for this user'], 404);
        }
        return response()->json($user_recommendations);
    }
}
