<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)
            ->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'success' => false,
                'message' => "Incorrect email or password."
            ], 403);
        }
        try {
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return $this->respondWithToken($user);
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name'     => $request->user['name'],
            'email'    => $request->user['email'],
            'password' => Hash::make($request->user['password'])
        ]);
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
    public function logout()
    {
       return JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    private function respondWithToken($user)
    {
        $token = JWTAuth::fromUser($user);

        return response()->json([
        "Authorization" => "Bearer $token",
        'user' => $user
    ]);
    }

}
