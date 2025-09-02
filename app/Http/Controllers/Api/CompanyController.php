<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Include user relationship for better frontend display
        $companies = Company::with('user')->get();
        return response()->json($companies);
    }

    public function show(Request $request,$id)
    {
        $company = Company::with('user')->findOrFail($id);

        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($request->user()->hasRole('super_admin') || $request->user()->id === $company->user_id) {
            return response()->json($company);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function update(Request $request, $id)
    {
        try
        {
            $company = Company::findOrFail($id);
            // if (!$request->user()->hasRole('super_admin') && $request->user()->id !== $company->user_id) {
            if (!$request->user()->hasRole('super_admin')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'name'          => 'nullable|string|max:255',
                'address'       => 'nullable|string',
                'website'       => 'nullable|string|max:255',
                'description'   => 'nullable|string',
                'is_verified'   => 'nullable|boolean',
            ]);

            if (isset($validated['is_verified']) && !$request->user()->hasRole('super_admin')) {
                unset($validated['is_verified']);
            }

            if (isset($validated['name'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            $company->update($validated);
            // Return company with user relationship for frontend
            return response()->json($company->load('user'));
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

    public function destroy(Request $request,$id)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $company = Company::findOrFail($id);
        $company->delete();
        return response()->json(['message' => 'Company deleted']);
    }

    /**
     * Get all pending (unverified) companies
     */
    public function pending(Request $request)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $pendingCompanies = Company::with('user')
            ->where('is_verified', false)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($pendingCompanies);
    }

    /**
     * Get all verified companies
     */
    public function verified(Request $request)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $verifiedCompanies = Company::with('user')
            ->where('is_verified', true)
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return response()->json($verifiedCompanies);
    }

    /**
     * Verify a company
     */
    public function verify(Request $request, $id)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $company = Company::findOrFail($id);
            
            $company->update([
                'is_verified' => true,
                'updated_at' => now()
            ]);
            
            return response()->json([
                'message' => 'Company verified successfully',
                'company' => $company->load('user')
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Unverify/revoke verification of a company
     */
    public function unverify(Request $request, $id)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $company = Company::findOrFail($id);
            
            $company->update([
                'is_verified' => false,
                'updated_at' => now()
            ]);
            
            return response()->json([
                'message' => 'Company verification revoked successfully',
                'company' => $company->load('user')
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Get the authenticated user's company
     */
    public function myCompany(Request $request)
    {
        if (!$request->user()->hasRole('company')) {
            return response()->json(['error' => 'User is not associated with a company'], 403);
        }

        $company = $request->user()->company;
        
        if (!$company) {
            return response()->json(['error' => 'No company found for this user'], 404);
        }

        return response()->json($company->load('user'));
    }
}
