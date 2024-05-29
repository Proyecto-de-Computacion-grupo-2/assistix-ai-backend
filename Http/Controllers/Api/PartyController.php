<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use function Laravel\Prompts\error;

class PartyController extends Controller
{
    /**
     *
     * Get all the absences for a specific player.
     *
     * @param $id party id.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllParties()
    {
        $parties = Party::all();
        if ($parties->isEmpty()) {
            return response()->json(['message' => 'No parties found.'], 404);
        }
        return response()->json($parties);
    }

    public function postParty($playerId, $type, $date)
    {
        $party = new Party();
        $party->id_mundo_deportivo = $playerId;
        $party->type = $type;
        $party->date = $date;
        $party->save();
        error_log($party);
        return response()->json([], 200);
    }

}
