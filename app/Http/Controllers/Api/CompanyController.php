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
        return response()->json(Company::all());
    }

    public function show(Request $request,$id)
    {
        $company = Company::findOrFail($id);

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
            return response()->json($company);
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
}
