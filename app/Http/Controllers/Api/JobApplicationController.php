<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Controllers\Controller;
use Exception;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->hasRole('company')) {
            $applications = JobApplication::whereHas('job', function ($query) use ($request) {
                $query->where('company_id', $request->user()->company->id);
            })->get();
        } elseif ($request->user()->hasRole('candidate')) {
            $applications = JobApplication::where('candidate_id', $request->user()->candidate->id)->get();
        } else {
            $applications = JobApplication::all();
        }
        return response()->json($applications);
    }

    public function show(Request $request, $id)
    {
        $application = JobApplication::findOrFail($id);
        if ($request->user()->hasRole('super_admin') ||
            ($request->user()->hasRole('company') && $application->job->company_id === $request->user()->company->id) ||
            ($request->user()->hasRole('candidate') && $application->candidate_id === $request->user()->candidate->id)) {
            return response()->json($application);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function store(Request $request)
    {
        try{
            if (!$request->user()->hasRole('candidate')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'job_id'        => 'required|exists:all_jobs,id',
                'cover_letter'  => 'nullable|string',
            ]);

            $application = JobApplication::create(array_merge($validated, [
                'candidate_id' => $request->user()->candidate->id,
            ]));

            return response()->json($application, 201);
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
        $application = JobApplication::findOrFail($id);
        if (!$request->user()->hasRole('company') || $application->job->company_id !== $request->user()->company->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'in:pending,reviewed,accepted,rejected',
        ]);

        $application->update($validated);
        return response()->json($application);
    }

    public function destroy(Request $request, $id)
    {
        $application = JobApplication::findOrFail($id);
        if (!$request->user()->hasRole('super_admin') && $request->user()->candidate?->id !== $application->candidate_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $application->delete();
        return response()->json(['message' => 'Application deleted']);
    }
}
