<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Http\Controllers\API\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255',
            'password' => 'required|max:15'
        ], [
            'username.required' => 'Username is required.',
            'username.max' => 'Username has character count over limit.',
            'password.required' => 'Password is required.',
            'password.max' => 'Password has character count over limit.'
        ]);
        if ($validator->fails()) {
            $error_message = $validator->errors()->first();
            $json = json_encode([
                'message' => $error_message
            ]);
            return response($json, 400)
                    ->header('Content-Length', strlen($json))
                    ->header('Content-Type', 'application/json;charset=utf-8');
        }
        $username = $request->input('username');
        $password = $request->input('password');
        $proxy = new Proxy;
        return response($proxy->attemptLogin($username, $password), $proxy->statusCode);    
    }

    public function logout(Request $request) {
        $accessToken = $request->user()->token();
        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);
        $accessToken->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
