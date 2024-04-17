<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PriceVariation;
use Illuminate\Http\Request;

class PriceVariationController extends Controller
{
    public function getPlayerValue($player_id)
    {
        $player = Player::where('id_mundo_deportivo', $player_id)->first(['id_mundo_deportivo', 'full_name', 'player_value', 'photo_body', 'photo_face']);

        $price_variations = PriceVariation::where('id_mundo_deportivo', $player_id)->get(['price_day','price','is_prediction']);
        if ($price_variations->isEmpty()) {
            return response()->json(['message' => 'No price information found for this player'], 404);
        }

        $player->price_variations = $price_variations;
        return response()->json($player);
    }
}
