<section class="job-categories py-5 bg-light-gray">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-secondary-subtle text-secondary rounded-pill">Categories</span>
            <h2 class="fw-bold mt-2">Top Job Categories</h2>
            <p class="text-muted">Explore popular job categories and find your next career opportunity.</p>
        </div>

        {{-- All categories  --}}
        <div class="row g-4">
            @if(isset($categories) && count($categories) > 0)
                @foreach($categories as $category)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <a href="{{ route('jobs.byCategory', $category->id) }}" class="text-decoration-none text-dark">
                            <div class="card card-hover rounded-4 border-0 shadow-sm p-4 text-center"
                                    style="cursor: pointer;">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="{{ $category->getCategoryIcon() }} fa-2x text-primary"></i>
                                </div>
                                <h5 class="card-title fw-bold">{{ $category->name }}</h5>
                                <p class="card-text text-muted">
                                    {{ $category->jobs_count }} {{ $category->jobs_count == 1 ? 'Job' : 'Jobs' }}
                                </p>
                                @if($category->jobs_count > 0)
                                    <small class="text-success">Available Now</small>
                                @else
                                    <small class="text-muted">Coming Soon</small>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No job categories available at the moment.</p>
                </div>
            @endif
        </div>
        {{--  --}}
    </div>
</section>

<style>
.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-hover:hover .card-icon i {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

.card-hover:hover .card-title {
    color: #0d6efd !important;
}
</style>
