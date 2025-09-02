<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Company;
use App\Models\Candidate;
use \Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function registerView()
    {
        return view('frontend.pages.authentication.registration');
    }

    public function loginView()
    {
        return view('frontend.pages.authentication.login');
    }

    public function register(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'name'          => ['required', 'string', 'max:255'],
                'email'         => ['required', 'email:rfc,dns', 'max:255', 'unique:users'],
                'password'      => ['required', 'confirmed', Password::defaults()],
                'role'          => ['required', 'in:company,candidate'],
                'company_name'  => ['required_if:role,company', 'string', 'max:255'],
                'address'       => ['required_if:role,company', 'string'],
                'website'       => ['nullable', 'string', 'max:255'],
                'first_name'    => ['required_if:role,candidate', 'string', 'max:255'],
                'last_name'     => ['required_if:role,candidate', 'string', 'max:255'],
            ]);

            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
            ]);

            // Assign role using Spatie
            $user->assignRole($validated['role']);

            if ($validated['role'] === 'company') {
                Company::create([
                    'user_id'       => $user->id,
                    'name'          => $validated['company_name'],
                    'slug'          => Str::slug($validated['company_name']),
                    'address'       => $validated['address'],
                    'website'       => $validated['website'] ?? null,
                    'is_verified'   => false,
                ]);
            } elseif ($validated['role'] === 'candidate') {
                Candidate::create([
                    'user_id'       => $user->id,
                    'first_name'    => $validated['first_name'],
                    'last_name'     => $validated['last_name'],
                ]);
            }

            $token = $user->createToken($request->userAgent() ?? 'api')->plainTextToken;

            return response()->json([
                'message'       => 'User registered successfully',
                'access_token'  => $token,
                'token_type'    => 'Bearer',
                'user'          => $user->load('roles'),
            ], 201);
        }
        catch(Exception $exception)
        {
            return response()->json(
                [
                    'error' => $exception->getMessage()
                ]
            );
        }
    }

    public function login(Request $request)
    {
        try
        {
            $credentials = $request->validate([
                'email'     => ['required', 'email:rfc,dns'],
                'password'  => ['required'],
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = User::where('email',$credentials['email'])->first();

            if(!$user || !Hash::check($credentials['password'],$user->password))
            {
                return response()->json([
                    'message' => 'Invalid credentials',
                ],401);
            }

            $token = $user->createToken($request->userAgent() ?? 'api')->plainTextToken;

            return response()->json([
                'message'       => 'Login successful',
                'access_token'  => $token,
                'token_type'    => 'Bearer',
                'user'          => $user->load('roles'),
            ]);
        }
        catch(Exception $exception)
        {
            return response()->json(
                [
                    'error' => $exception->getMessage()
                ]
            );
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout successful']);
    }

    /**
     * Get the current authenticated user's profile with roles
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user()->load('roles');
            
            // Get role names for easier frontend checking
            $roleNames = $user->getRoleNames()->toArray();
            
            return response()->json([
                'user' => $user,
                'roles' => $roleNames,
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray()
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 500);
        }
    }
}
