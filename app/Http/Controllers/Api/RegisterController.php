<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\FirebaseToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function __contruct()
    {
        $this->middleware('auth:api', ['except' => ['register']]);
    }

    public function register(Request $request)
    {
        $data = $request->only('first_name', 'last_name', 'email', 'password', 'phone');
        $user = User::create($data);

        if (empty($user)) {
            return response()->json([
                'message' => 'Unable to create account'
            ], 300);
        }

        if ($request->has('device_identifier') && $request->has('device_token')) {
            $firebaseToken = new FirebaseToken($request->only('device_identifier', 'device_token'));
            $firebaseToken->user_id = $user->id;

            if ($request->has('device_type')) {
                $firebaseToken->device_type = $request->input('device_type');
            }

            $firebaseToken->save();
        }

        if ( ! $token = $this->guard()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Account created! Please login',
            ], 300);
        }

        return [
            'user' => $user,
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60,
            ],
        ];
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }
}