<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeagueUser;

class LeagueUserController extends Controller
{
    /**
     * Get the Users table in the frontend.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersRankingTable()
    {
        $user = LeagueUser::select('id_user', 'team_name', 'team_points')->get();
        if ($user->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }
        return response()->json($user);
    }

    /**
     * Get the user money details.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserMoneyDetails($id)
    {
        $league_user = LeagueUser::find($id, ['id_user', 'team_name','current_balance', 'future_balance', 'maximum_debt', 'team_points']);
        if (!$league_user) {
            return response()->json(['message' => 'Player not found'], 404);
        }
        return response()->json($league_user);
    }

    /**
     * Update the user email and password in the database when the user gets logged in.
     *
     * @param $id
     * @param $email
     * @param $password
     * @return \Illuminate\Http\JsonResponse|void
     */
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

    /**
     * Get all the recommendations for a user.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecommendationsLeagueUser($id)
    {
        $league_user = LeagueUser::with(['recommendations.player'])->where('id_user', $id)->get();

        if ($league_user->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }

        $transformed = $league_user->map(function ($leagueUser) {
            return $leagueUser->recommendations->map(function ($recommendation) {
                return [
                    'id_mundo_deportivo' => $recommendation->id_mundo_deportivo,
                    'full_name' => $recommendation->player->full_name,
                    'player_value' => $recommendation->player->player_value,
                    'position' => $recommendation->player->position,
                    'photo_face' => $recommendation->player->photo_face,
                    'season_23_24' => $recommendation->player->season_23_24,
                    'operation_type' => $recommendation->operation_type,
                    'expected_value_percentage' => $recommendation->expected_value_percentage
                ];
            });
        })->flatten(1);

        return response()->json($transformed);
    }

    /**
     * Get the user admin info.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAdminInfo()
    {
        $league_user = LeagueUser::select(['id_user', 'email', 'team_name', 'active'])->get();
        if (!$league_user) {
            return response()->json(['message' => 'Users not found'], 404);
        }
        $sorted = $league_user->sortBy('id_user');
        $sorted->shift(); // Remove the first element from the collection.

        return response()->json(array_values($sorted->toArray()));
    }

    /**
     * Activate or deactivate a user.
     * @param $id
     * @param $active
     * @return mixed
     */
    public function activateUser($id, $active)
    {
        $user = LeagueUser::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($active == 'true') {
            $active = 1;
        } else {
            $active = 0;
        }

        $user->active = $active;
        return $user->save();
    }
}
