<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\LeagueUserController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * Check if user exists and add password upon register.
     * @return JsonResponse
     */
    public function register()
    {
        $credentials = request(['id_user', 'email', 'password']);

        $league_user = LeagueUserController::userExistsId($credentials['id_user']);

        if (!$league_user) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        $insertFail = LeagueUserController::insertEmailPassword($credentials);
        if ($insertFail) {
            return $insertFail;
        }

        return response()->json(['Great success' => 'Registered correctly']);
    }


    /**
     * Check if user entered a valid password upon login.
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        $league_user = LeagueUserController::userExistsEmail($credentials['email']);
        if (!$league_user) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        $isValid = LeagueUserController::isPasswordValid($credentials);

        if (is_bool($isValid)) {
            if ($isValid) {
                if (!$token = auth()->attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            return $isValid;
        }
    }
}
