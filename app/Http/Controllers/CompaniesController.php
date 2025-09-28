<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllCompanies()
    {
        return view('backend.pages.companies.all-companies');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getPendingCompanies()
    {
          return view('backend.pages.companies.pending-companies');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getVerifiedCompanies()
    {
          return view('backend.pages.companies.verified-companies');
    }

    /**
     * Display the specified resource.
     */
    public function verifyCompanyView()
    {
        return view('backend.pages.companies.verify-company');
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
    public function destroy(string $id)
    {
        //
    }
}
