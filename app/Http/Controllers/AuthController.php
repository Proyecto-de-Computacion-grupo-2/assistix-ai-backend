<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\LeagueUserController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        $insertFail = LeagueUserController::insertEmailPassword($credentials, $league_user);
        if ($insertFail) {
            return $insertFail;
        }

        return response()->json(['Great success' => 'Registrado correctamente.']);
    }


    /**
     * Check if user entered a valid password upon login.
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['id_user', 'email', 'password']);

        $league_user = LeagueUserController::userExistsEmail($credentials['email']);
        if (!$league_user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        $isValid = LeagueUserController::isPasswordValid($credentials['password'], $league_user);

        if ($isValid) {
            if (!$token = auth()->login($league_user)) {
                return response()->json(['error' => 'No autorizado.'], 401);
            }

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        } else {
            return response()->json(['error' => 'Contraseña no válida.'], 401);
        }
    }
}
