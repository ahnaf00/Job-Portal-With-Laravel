<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        try{
            if (!$request->user()->hasRole('super_admin')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return response()->json(Candidate::all());
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

    public function show(Request $request,$id)
    {
        try{
            $candidate = Candidate::findOrFail($id);
            if ($request->user()->hasAnyRole(['super_admin', 'company']) || $request->user()->id === $candidate->user_id) {
                return response()->json($candidate);
            }
            return response()->json(['error' => 'Unauthorized'], 403);
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

    public function update(Request $request, $id)
    {
        try
        {
            $candidate = Candidate::findOrFail($id);
            if (!$request->user()->hasAnyRole(['super_admin', 'candidate']) && $request->user()->id !== $candidate->user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'first_name'    => 'string|max:255',
                'last_name'     => 'string|max:255',
                'phone'         => 'string|max:20',
                'resume'        => 'string',
                'skills'        => 'string',
                'education'     => 'string',
                'experience'    => 'string',
            ]);

            $candidate->update($validated);
            return response()->json($candidate);
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
        $candidate = Candidate::findOrFail($id);
        if (!$request->user()->hasRole('super_admin') && $request->user()->id !== $candidate->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $candidate->delete();
        return response()->json(['message' => 'Candidate deleted']);
    }
}
