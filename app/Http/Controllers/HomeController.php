<?php

namespace App\Http\Controllers;

use App\Models\AllJob;
use App\Models\JobCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homePage()
    {
        $categories = JobCategory::withJobCounts(true)->get();
        $featuredJobs = AllJob::where("is_featured",1)->get();

        return view('frontend.pages.home', compact('categories','featuredJobs'));
    }
}
