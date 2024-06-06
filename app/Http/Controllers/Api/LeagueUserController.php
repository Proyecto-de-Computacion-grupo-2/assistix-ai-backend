<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeagueUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class LeagueUserController extends Controller
{

    /**
     * Find user in DD BB with ID.
     * @param $id
     * @return mixed
     */
    private static function findUserId($id)
    {
        $league_user = LeagueUser::find($id, ['id_user', 'email', 'password']);
        if (!$league_user) {
            return false;
        }
        return $league_user;
    }

    /**
     * Find user in DD BB with email.
     * @param $email
     * @return mixed
     */
    private static function findUserEmail($email)
    {
        $league_user = LeagueUser::where('email', $email)->first();
        if (!$league_user) {
            return false;
        }
        return $league_user;
    }

    /**
     * Does user exist.
     * @param $id
     * @return false | array
     */
    public static function userExistsId($id)
    {
        $league_user = self::findUserId($id);
        if (!$league_user) {
            return false;
        }
        return $league_user;
    }

    /**
     * Does user exist, with email.
     * @param $email
     * @return false | array
     */
    public static function userExistsEmail($email)
    {
        $league_user = self::findUserEmail($email);
        if (!$league_user) {
            return false;
        }
        return $league_user;
    }

    /**
     * Does user have password.
     * @param $id
     * @return bool
     */
    public static function hasPassword($id)
    {
        $league_user = self::findUserId($id);
        if (!$league_user) {
            return false;
        } else {
            if (!$league_user['password']) {
                return false;
            }
            return true;
        }
    }

    /**
     * Does user have email.
     * @param $id
     * @return bool
     */
    public static function hasEmail($id)
    {
        $league_user = self::findUserId($id);
        if (!$league_user) {
            return false;
        } else {
            if (!$league_user['email']) {
                return false;
            }
            return true;
        }
    }

    /**
     * Encrypt given password.
     * @param $password
     * @return JsonResponse | string
     */
    private static function encryptPassword($password)
    {
        $publicKeyPath = base_path('app/keys/public_key_16184.pem');
        if (!File::exists($publicKeyPath)) {
            Log::error('Check public key path.');
            return response()->json(['error' => 'Código de error #19510, ponte en contacto con nosotros por favor.'], 500);
        }

        $publicKeyString = File::get($publicKeyPath);
        $publicKey = openssl_get_publickey($publicKeyString);

        if (!$publicKey) {
            Log::error('Error loading public key.');
            return response()->json(['error' => 'Código de error #19520, ponte en contacto con nosotros por favor.'], 500);
        } else {
            if (openssl_public_encrypt($password, $encryptedPassword, $publicKey)) {
                return utf8_encode(base64_encode($encryptedPassword));
            }
            Log::debug("OpenSSL error: " . openssl_error_string());
            return response()->json(['error' => 'Código de error #19530, ponte en contacto con nosotros por favor.'], 500);
        }
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
        $privateKeyPath = base_path('app/keys/private_key_16184.pem');
        if (!File::exists($privateKeyPath)) {
            Log::error('Check private key path.');
            return response()->json(['error' => 'Código de error #19540, ponte en contacto con nosotros.'], 500);
        }

        $privateKeyString = File::get($privateKeyPath);
        $privateKey = openssl_get_privatekey($privateKeyString);

        $encryptedPasswordBase64 = $password;

        if (!$privateKey) {
            Log::error('Error loading private key.');
            return response()->json(['error' => 'Código de error #19550, ponte en contacto con nosotros por favor.'], 500);
        } else {
            $encryptedPassword = base64_decode(utf8_decode($encryptedPasswordBase64));
            if (openssl_private_decrypt($encryptedPassword, $decryptedPassword, $privateKey)) {
                return $decryptedPassword;
            }
            Log::debug("OpenSSL error: " . openssl_error_string());
            return response()->json(['error' => 'Código de error #19560, ponte en contacto con nosotros por favor.'], 500);
        }
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
     * Compare given password with the registered one.
     * @param $password
     * @param $original
     * @return bool
     */
    private static function comparePassword($password, $original)
    {
        $decryptedPassword = self::callToDecrypt($original);

        if ($decryptedPassword === $password) {
            return $original;
        }
        return false;
    }

    /**
     * Check if user entered a valid password upon login.
     * @param $password
     * @param $league_user
     * @return JsonResponse | bool
     */
    public static function isPasswordValid($password, $league_user)
    {
        if (!self::hasEmail($league_user['id_user'])) {
            return response()->json(['error' => 'Error, el usuario no tiene un email registrado.'], 404);
        }
        if (!self::hasPassword($league_user['id_user'])) {
            return response()->json(['error' => 'Error, el usuario no tiene una contraseña registrada.'], 404);
        }

        return self::comparePassword($password, $league_user->password);
    }

    /**
     * Insert user password.
     * @param $credentials
     * @param $league_user
     * @return JsonResponse | bool
     */
    public static function insertEmailPassword($credentials, $league_user)
    {
        if (!$league_user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if (self::hasEmail($league_user['id_user'])) {
            return response()->json(['error' => 'Error, el email ya ha sido registrado.'], 403);
        }

        if (self::hasPassword($league_user['id_user'])) {
            return response()->json(['error' => 'Error, usuario ya registrado.'], 403);
        }

        $credentials['password'] = self::callToEncrypt($credentials['password']);

        if (!Str::contains($credentials['password'], '500 Internal Server Error')) {
            $league_user->email = $credentials['email'];
            $league_user->password = $credentials['password'];
            $league_user->save();
            return false;
        }
        return $credentials['password'];
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
