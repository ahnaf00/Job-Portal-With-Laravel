<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\AllJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class AllJobController extends Controller
{
    public function index()
    {
        try
        {
            return response()->json(data: AllJob::where('is_published', true)->get());
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

    public function show(Request $request, $id)
    {
        $job = AllJob::findOrFail($id);
        if (!$job->is_published && !$request->user()->hasRole('super_admin') && $request->user()->company?->id !== $job->company_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json($job);
    }

    public function store(Request $request)
    {
        try
        {
            if (!$request->user()->hasAnyRole(['company', 'super_admin'])) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            if ($request->user()->hasRole('company') && !$request->user()->company->is_verified) {
                return response()->json(['error' => 'Company must be verified to post jobs'], 403);
            }

            $validated = $request->validate([
                'category_id'   => 'required|exists:job_categories,id',
                'title'         => 'required|string|max:255',
                'description'   => 'required|string',
                'location'      => 'required|string|max:255',
                'salary_min'    => 'nullable|integer',
                'salary_max'    => 'nullable|integer',
                'job_type'      => 'required|in:full-time,part-time,remote,contract',
                'is_featured'   => 'boolean',
                'is_published'  => 'boolean',
            ]);

            $job = AllJob::create(array_merge($validated, [
                'company_id' => $request->user()->company->id,
                'slug' => Str::slug($validated['title']),
            ]));

            return response()->json($job, 201);
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
            $job = AllJob::findOrFail($id);
            if (!$request->user()->hasRole('super_admin') && $request->user()->company?->id !== $job->company_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'category_id'   => 'exists:job_categories,id',
                'title'         => 'string|max:255',
                'description'   => 'string',
                'location'      => 'string|max:255',
                'salary_min'    => 'nullable|integer',
                'salary_max'    => 'nullable|integer',
                'job_type'      => 'in:full-time,part-time,remote,contract',
                'is_featured'   => 'boolean',
                'is_published'  => 'boolean',
            ]);

            if (isset($validated['title'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            $job->update($validated);
            return response()->json($job);
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

    public function destroy(Request $request, $id)
    {
        $job = AllJob::findOrFail($id);
        if (!$request->user()->hasRole('super_admin') && $request->user()->company?->id !== $job->company_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $job->delete();
        return response()->json(['message' => 'Job deleted']);
    }
}
