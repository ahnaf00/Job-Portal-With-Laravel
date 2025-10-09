@extends('frontend.layouts.master')

@section('frontend-content')
    @include('Frontend.pages.header')
    <main>
        @include('Frontend.pages.hero')
        @include('Frontend.pages.jobCategories')
        @include('Frontend.pages.featuredJobs')
        @include('Frontend.pages.howItWorks')
        @include('Frontend.pages.discover')
        @include('Frontend.pages.statsSection')
        @include('Frontend.pages.testimonials')
        @include('Frontend.pages.mobileApp')
    </main>
    @include('frontend.pages.footer')
@endsection
