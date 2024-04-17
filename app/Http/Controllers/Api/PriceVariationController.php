<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PriceVariation;
use Illuminate\Http\Request;

class PriceVariationController extends Controller
{
    /**
     * Retrieves detailed information for a specific player identified by their ID.
     * This information includes player basic details such as ID, full name, player value,
     * and their photos. Additionally, it fetches and returns all price variations associated
     * with this player.
     *
     * If the player cannot be found, a 404 JSON response is returned with an appropriate message.
     * Similarly, if no price variations are found for the player, a 404 response is also returned,
     * noting the absence of price data.
     *
     * @param int $player_id The ID of the player to fetch.
     * @return \Illuminate\Http\JsonResponse Returns the player information along with their
     *         price variations in JSON format. If no player or no price data is found, returns
     *         a 404 response with an error message.
     */
    public function getPlayerValue($player_id)
    {
        $player = Player::where('id_mundo_deportivo', $player_id)->first(['id_mundo_deportivo', 'full_name', 'player_value', 'photo_body', 'photo_face']);

        $price_variations = PriceVariation::where('id_mundo_deportivo', $player_id)->get(['price_day', 'price', 'is_prediction']);
        if ($price_variations->isEmpty()) {
            return response()->json(['message' => 'No price information found for this player'], 404);
        }

        $player->price_variations = $price_variations;
        return response()->json($player);
    }
}
