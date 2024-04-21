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
        $league_user = LeagueUser::find($id, ['id_user', 'current_balance', 'future_balance', 'maximum_debt', 'team_points']);
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
        $league_user = LeagueUser::with(['recommendations.player.games' => function ($query) {
            $query->orderBy('game_week', 'desc')->take(3);
        }])->where('id_user', $id)->get();

        if ($league_user->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }

        $transformed = $league_user->map(function ($leagueUser) {
            return $leagueUser->recommendations->map(function ($recommendation) {
                return [
                    'id_mundo_deportivo' => $recommendation->id_mundo_deportivo,
                    'full_name' => $recommendation->player->full_name,
                    'operation_type' => $recommendation->operation_type,
                    'expected_value_percentage' => $recommendation->expected_value_percentage,
                    'photo_face' => $recommendation->player->photo_face,
                    'games' => $recommendation->player->games->map(function ($game) {
                        return [
                            'game_week' => $game->game_week,
                            'mixed' => $game->mixed,
                        ];
                    }),
                ];
            });
        })->flatten(1);

        return response()->json($transformed);
    }

}
