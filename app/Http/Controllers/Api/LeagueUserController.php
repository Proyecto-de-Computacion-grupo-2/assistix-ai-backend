<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeagueUser;

class LeagueUserController extends Controller
{
    public function getUsersRankingTable()
    {
        $users = LeagueUser::select('team_name','team_points','team_average','team_value','team_players')->get();
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }
        return response()->json($users);
    }
}
