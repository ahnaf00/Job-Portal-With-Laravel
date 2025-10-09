<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Company;
use App\Models\Candidate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class AuthenticationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function registerView()
    {
        return view('frontend.pages.authentication.registration');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function loginView()
    {
        return view('frontend.pages.authentication.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        try
        {
            $credentials = $request->validate([
                'email'     => ['required', 'email:rfc,dns'],
                'password'  => ['required'],
            ]);

            if(Auth::attempt($credentials,$request->filled('remember')))
            {
                $request->session()->regenerate();
                return redirect()->route('dashboard')->with('success','Login Successful');
            }

            return back()->withErrors([
                'email' => "Invalid credentials provided",
            ])->withInput();
        }
        catch(Exception $exception)
        {
            return back()->withErrors([
                'error' => 'Something went wrong: '. $exception->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success','Logged out successful');
    }
}
