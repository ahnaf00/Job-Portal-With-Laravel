<section class="featured-jobs py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-secondary-subtle text-secondary rounded-pill">Jobs</span>
            <h2 class="fw-bold mt-2">Featured Jobs</h2>
            <p class="text-muted">Find your dream job with top employers.</p>
        </div>
        <div class="row g-4">
            <!-- Job Card 1 -->

            @foreach ($featuredJobs as $featuredJob)
                <div class="col-md-6">
                    <div class="card job-card rounded-4 border-0 shadow-sm p-4 d-flex flex-row align-items-center">
                        <img src="https://placehold.co/80x80/285F5D/fff?text=C" class="rounded-3 me-3" alt="Company Logo">
                        <div class="flex-grow-1">
                            <h5 class="card-title fw-bold mb-1">{{ $featuredJob->title }}</h5>
                            <p class="card-text text-muted mb-1">Creative Agency, New York</p>
                            <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $featuredJob->job_type }}</span>
                        </div>
                        <div class="text-end">
                            <div class="text-primary fw-bold mb-1">${{ $featuredJob->salary_min }} - ${{ $featuredJob->salary_max }}</div>
                            <small class="text-muted">2 days ago</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="#" class="btn btn-primary text-white rounded-pill">Explore More Jobs</a>
        </div>
    </div>
</section>
