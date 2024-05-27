<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeagueUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class LeagueUserController extends Controller
{

    /**
     * Find user in DD BB with ID.
     * @param $id
     * @return array
     */
    private static function findUserId($id)
    {
        $league_user = LeagueUser::find($id, ['id_user', 'email', 'password']);
        if (!$league_user) {
            return [false, false];
        }
        return [$league_user, response()->json($league_user)];
    }

    /**
     * Find user in DD BB with email.
     * @param $email
     * @return array
     */
    private static function findUserEmail($email)
    {
        $league_user = LeagueUser::where('email', $email)->first(['id_user', 'email', 'password']);
        if (!$league_user) {
            return [false, false];
        }
        return [$league_user, response()->json($league_user)];
    }

    /**
     * Does user have password.
     * @param $id
     * @return bool
     */
    public static function hasPassword($id)
    {
        $league_user = self::findUserId($id)[0];
        if (!$league_user) {
            return false;
        } else {
            if (!$league_user['password']) {
                return false;
            } else {
                return true;
            }
        }

    }

    /**
     * Compare given password with the registered one.
     * @param $password
     * @param $original
     * @return bool
     */
    private static function comparePassword($password, $original)
    {
        $decryptedPassword = self::callToDecrypt($original);

        if ($decryptedPassword === $password) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Encrypt given password.
     * @param $password
     * @return JsonResponse | string
     */
    private static function encryptPassword($password)
    {
        $publicKeyPath = base_path('app/public_key_16184.pem');
        if (!File::exists($publicKeyPath)) {
            return response()->json(['error' => 'P16K not found'], 500);
        }

        $publicKey = File::get($publicKeyPath);

        openssl_public_encrypt($password, $encryptedPassword, $publicKey);

        return base64_encode($encryptedPassword);
    }

    /**
     * Call encrypting function.
     * @param $password
     * @return JsonResponse | string
     */
    public static function callToEncrypt($password)
    {
        return self::encryptPassword($password);
    }

    /**
     * Decrypt given password.
     * @param $password
     * @return JsonResponse | string
     */
    private static function decryptPassword($password)
    {
        $privateKeyPath = base_path('app/private_key_16184.pem');
        if (!File::exists($privateKeyPath)) {
            return response()->json(['error' => 'Pr16K error.'], 500);
        }

        $privateKey = File::get($privateKeyPath);

        $encryptedPasswordBase64 = $password;

        $encryptedPassword = base64_decode($encryptedPasswordBase64);

        openssl_private_decrypt($encryptedPassword, $decryptedPassword, $privateKey);

        return $decryptedPassword;
    }

    /**
     * Call decryption function.
     * @param $password
     * @return JsonResponse | string
     */
    public static function callToDecrypt($password)
    {
        return self::decryptPassword($password);
    }

    /**
     * Does user exist.
     * @param $id
     * @return bool
     */
    public static function userExistsId($id)
    {
        $league_user = self::findUserId($id)[0];
        if (!$league_user) {
            return false;
        }
        return true;
    }

    /**
     * Does user exist, with email.
     * @param $email
     * @return bool
     */
    public static function userExistsEmail($email)
    {
        $league_user = self::findUserEmail($email)[0];
        if (!$league_user) {
            return false;
        }
        return true;
    }

    /**
     * Check if user entered a valid password upon login.
     * @param $credentials
     * @return JsonResponse | bool
     */
    public static function isPasswordValid($credentials)
    {
        $league_user = self::findUserId($credentials['id_user'])[1];
        $league_user_object = self::findUserId($credentials['id_user'])[0];
        if (!$league_user) {
            return false;
        }

        if (!self::hasPassword($credentials['id_user'])) {
            return response()->json(['error' => 'Error, user doesn\'t have registered password.'], 404);
        }

        return self::comparePassword($credentials['password'], $league_user_object->password);
    }

    /**
     * Insert user password.
     * @param $credentials
     * @return JsonResponse | bool
     */
    public static function insertEmailPassword($credentials)
    {
        $league_user = self::findUserId($credentials['id_user'])[1];
        $league_user_object = self::findUserId($credentials['id_user'])[0];
        if (!$league_user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if (self::hasPassword($credentials['id_user'])) {
            return response()->json(['error' => 'Error, user already registered.'], 403);
        }

        $credentials['password'] = self::callToEncrypt($credentials['password']);

        $league_user_object->email = $credentials['email'];
        $league_user_object->password = $credentials['password'];
        $league_user_object->save();
        return false;
    }

    /**
     * Get the Users table in the frontend.
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard_get_users_ranking_table()
    {
        $user = LeagueUser::select('id_user', 'team_name', 'team_points', 'team_value')
            ->where('id_user', '!=', 1010)
            ->where('admin', '!=', 1)
            ->orderBy('team_points', 'desc')
            ->get();

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
    public function layout_get_user_details($id)
    {
        $league_user = LeagueUser::find($id, ['id_user', 'team_players', 'team_name', 'current_balance', 'future_balance', 'maximum_debt', 'team_points']);
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
    public function add_user_login_credentials($id, $email, $password)
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
    public function get_recommendations_league_user($id)
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
    public function admin_get_users_info()
    {
        $league_user = LeagueUser::select(['id_user', 'email', 'team_name', 'active'])->where('admin', '!=', 1)->get();
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
    public function admin_activate_User($id, $active)
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
