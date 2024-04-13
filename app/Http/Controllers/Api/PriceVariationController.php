<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PriceVariation;
use Illuminate\Http\Request;

class PriceVariationController extends Controller
{
    public function getPriceVariationsPlayer($player_id)
    {
        $price_variations = PriceVariation::where('id_mundo_deportivo', $player_id)->get();
        if ($price_variations->isEmpty()) {
            return response()->json(['message' => 'No price variation found for this player'], 404);
        }
        return response()->json($price_variations);

    }
}
