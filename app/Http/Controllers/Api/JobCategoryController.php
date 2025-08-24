<?php

namespace App\Http\Controllers\Api;

use App\Models\JobCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class JobCategoryController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(JobCategory::all());
    }

    public function show(Request $request, $id)
    {
        return response()->json(JobCategory::findOrFail($id));
    }

    public function store(Request $request)
    {
        try
        {
            if (!$request->user()->hasRole('super_admin')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:job_categories',
            ]);

            $category = JobCategory::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
            ]);

            return response()->json($category, 201);
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
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $category = JobCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string|max:255|unique:job_categories,name,' . $id,
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json($category);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        JobCategory::findOrFail($id)->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}
