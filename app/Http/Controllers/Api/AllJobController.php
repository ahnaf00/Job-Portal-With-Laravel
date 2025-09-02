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
            $jobs = AllJob::with(['company', 'jobCategory'])
                ->where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json($jobs);
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
        $job = AllJob::with(['company', 'jobCategory', 'jobApplications'])->findOrFail($id);
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

            // Return job with relationships for frontend
            return response()->json($job->load(['company', 'jobCategory']), 201);
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

            // Check if company is trying to publish a job without verification
            if (isset($validated['is_published']) && $validated['is_published'] === true) {
                if ($request->user()->hasRole('company') && !$request->user()->company->is_verified) {
                    return response()->json(['error' => 'Company must be verified to publish jobs'], 403);
                }
            }

            if (isset($validated['title'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            $job->update($validated);
            // Return job with relationships for frontend
            return response()->json($job->load(['company', 'jobCategory']));
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

    /**
     * Get all jobs for admin view
     */
    public function adminIndex(Request $request)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        try {
            $jobs = AllJob::with(['company', 'jobCategory'])
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json($jobs);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Get jobs for the authenticated company
     */
    public function myJobs(Request $request)
    {
        if (!$request->user()->hasAnyRole(['company', 'super_admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $companyId = $request->user()->company?->id;
            if (!$companyId && !$request->user()->hasRole('super_admin')) {
                return response()->json(['error' => 'No company associated with user'], 404);
            }

            $query = AllJob::with(['company', 'jobCategory', 'jobApplications']);
            
            if (!$request->user()->hasRole('super_admin')) {
                $query->where('company_id', $companyId);
            }

            $jobs = $query->orderBy('created_at', 'desc')->get();
            return response()->json($jobs);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Get draft jobs (unpublished) for the authenticated company
     */
    public function draftJobs(Request $request)
    {
        if (!$request->user()->hasAnyRole(['company', 'super_admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $companyId = $request->user()->company?->id;
            if (!$companyId && !$request->user()->hasRole('super_admin')) {
                return response()->json(['error' => 'No company associated with user'], 404);
            }

            $query = AllJob::with(['company', 'jobCategory'])
                ->where('is_published', false);
            
            if (!$request->user()->hasRole('super_admin')) {
                $query->where('company_id', $companyId);
            }

            $jobs = $query->orderBy('created_at', 'desc')->get();
            return response()->json($jobs);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Publish a job
     */
    public function publish(Request $request, $id)
    {
        try {
            $job = AllJob::findOrFail($id);
            
            if (!$request->user()->hasRole('super_admin') && $request->user()->company?->id !== $job->company_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            if ($request->user()->hasRole('company') && !$request->user()->company->is_verified) {
                return response()->json(['error' => 'Company must be verified to publish jobs'], 403);
            }

            $job->update(['is_published' => true]);
            
            return response()->json([
                'message' => 'Job published successfully',
                'job' => $job->load(['company', 'jobCategory'])
            ]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Unpublish a job
     */
    public function unpublish(Request $request, $id)
    {
        try {
            $job = AllJob::findOrFail($id);
            
            if (!$request->user()->hasRole('super_admin') && $request->user()->company?->id !== $job->company_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $job->update(['is_published' => false]);
            
            return response()->json([
                'message' => 'Job unpublished successfully',
                'job' => $job->load(['company', 'jobCategory'])
            ]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Toggle featured status of a job
     */
    public function toggleFeatured(Request $request, $id)
    {
        try {
            $job = AllJob::findOrFail($id);
            
            if (!$request->user()->hasRole('super_admin') && $request->user()->company?->id !== $job->company_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $job->update(['is_featured' => !$job->is_featured]);
            
            return response()->json([
                'message' => $job->is_featured ? 'Job marked as featured' : 'Job unmarked as featured',
                'job' => $job->load(['company', 'jobCategory'])
            ]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
