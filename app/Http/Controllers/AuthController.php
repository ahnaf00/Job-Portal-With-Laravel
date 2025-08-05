<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','string','email:rfc,dns','max:255','unique:users'],
            'password' => ['required','confirmed',Password::defaults()],
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
        ]);

        $token = $user->createToken($request->userAgent() ?? 'api')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'     =>  ['required','email:rfc,dns'],
            'password'  =>  ['required']
        ]);

        $user = User::where('email',$credentials['email'])->first();

        if(!$user || !Hash::check($credentials['password'],$user->password))
        {
            return response()->json([
                'message' => 'Invalid credentials',
            ],401);
        }

        $token = $user->createToken($request->userAgent() ?? 'api')->plainTextToken;

        return response()->json([
            'message'       =>  'Login successful',
            'access_token'  =>  $token,
            'token_type'    =>  'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout successfull'
        ]);
    }
}
