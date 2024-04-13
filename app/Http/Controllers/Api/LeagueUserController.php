<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeagueUser;

class LeagueUserController extends Controller
{
    public function getUsersRankingTable()
    {
        $league_users = LeagueUser::select('team_name', 'team_points', 'team_average', 'team_value', 'team_players')->get();
        if ($league_users->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }
        return response()->json($league_users);
    }

    public function getUserMoneyDetails($id)
    {
        $league_user = LeagueUser::find($id, ['id_user', 'current_balance', 'future_balance', 'maximum_debt', 'team_points']);
        if (!$league_user) {
            return response()->json(['message' => 'Player not found'], 404);
        }
        return response()->json($league_user);
    }

    public function addUserLoginCredentials($id, $email, $password)
    {
        $user = LeagueUser::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->email = $email;
        $user->password = $password;
        $user->save();
    }

    public function getUser($id)
    {
        $user = LeagueUser::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);

    }

}
