<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GlobalRecommendation;

class GlobalRecommendationController extends Controller
{
    public function getGlobalRecommendationsGW($gw){

        $recommendations = GlobalRecommendation::where('gameweek', $gw)->get();
        if ($recommendations->isEmpty()) {
            return response()->json(['message' => 'No games found for this player'], 404);
        }
        return response()->json($recommendations);
    }
}
