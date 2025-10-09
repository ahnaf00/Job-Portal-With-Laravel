<?php

namespace App\Http\Controllers;

use App\Models\AllJob;
use App\Models\JobCategory;
use Hamcrest\Core\AllOf;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    // public function jobsPage()
    // {
    //     return view('frontend.pages.jobs');
    // }
    /**
     * Display a listing of the resource.
     */
    public function getAllJobs()
    {
        return view('backend.pages.jobs.all-jobs');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createJobView()
    {
        return view('backend.pages.jobs.create-job');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getMyJobs()
    {
        return view('backend.pages.jobs.my-jobs');
    }

    /**
     * Display the specified resource.
     */
    public function getDraftJobs()
    {
        return view('backend.pages.jobs.draft-jobs');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editJobView()
    {
        return view('backend.pages.jobs.edit-job');
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function jobsByCategory($id)
    {
        $category = JobCategory::findOrFail($id);
        $jobs = AllJob::where('category_id',$id)->get();
        return view('frontend.pages.jobs',compact('category', 'jobs'));
    }
}
