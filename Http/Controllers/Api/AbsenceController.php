<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    /**
     *
     * Get all the absences for a specific player.
     *
     * @param $id mundo deportivo player id.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAbsences($id)
    {
        $absences = Absence::where('id_mundo_deportivo', $id)->get();
        if ($absences->isEmpty()) {
            return response()->json(['message' => 'No absences found for this player'], 404);
        }
        return response()->json($absences);
    }
}
