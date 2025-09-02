<section class="job-categories py-5 bg-light-gray">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-secondary-subtle text-secondary rounded-pill">Categories</span>
            <h2 class="fw-bold mt-2">Top Job Categories</h2>
            <p class="text-muted">Explore popular job categories and find your next career opportunity.</p>
        </div>
        <div class="row g-4" id="categoriesContainer">
            <!-- Loading spinner -->
            <div class="col-12 text-center" id="loadingSpinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading categories...</span>
                </div>
                <p class="mt-2 text-muted">Loading categories...</p>
            </div>
            <!-- Categories will be loaded here -->
        </div>
        <!-- Error message container -->
        <div class="row" id="errorContainer" style="display: none;">
            <div class="col-12 text-center">
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorMessage">Unable to load job categories. Please try again later.</span>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Icon mapping for different category types
    const categoryIcons = {
        // Tech categories
        'software development': 'fas fa-laptop-code',
        'development': 'fas fa-laptop-code',
        'programming': 'fas fa-laptop-code',
        'web development': 'fas fa-code',
        'frontend': 'fas fa-code',
        'backend': 'fas fa-code',
        'full stack': 'fas fa-code',
        'mobile development': 'fas fa-mobile-alt',
        'app development': 'fas fa-mobile-alt',
        'data science': 'fas fa-chart-bar',
        'machine learning': 'fas fa-robot',
        'artificial intelligence': 'fas fa-robot',
        'cybersecurity': 'fas fa-shield-alt',
        'security': 'fas fa-shield-alt',
        'devops': 'fas fa-cogs',
        'cloud': 'fas fa-cloud',
        'database': 'fas fa-database',
        'qa': 'fas fa-bug',
        'testing': 'fas fa-bug',
        
        // Design categories
        'design': 'fas fa-palette',
        'graphic design': 'fas fa-palette',
        'ui design': 'fas fa-paint-brush',
        'ux design': 'fas fa-paint-brush',
        'web design': 'fas fa-paint-brush',
        'product design': 'fas fa-drafting-compass',
        'photography': 'fas fa-camera',
        'video editing': 'fas fa-video',
        'animation': 'fas fa-film',
        
        // Business categories
        'marketing': 'fas fa-chart-line',
        'digital marketing': 'fas fa-chart-line',
        'sales': 'fas fa-handshake',
        'business development': 'fas fa-briefcase',
        'project management': 'fas fa-tasks',
        'product management': 'fas fa-clipboard-list',
        'consulting': 'fas fa-user-tie',
        'finance': 'fas fa-dollar-sign',
        'accounting': 'fas fa-calculator',
        'hr': 'fas fa-users',
        'human resources': 'fas fa-users',
        'recruitment': 'fas fa-user-plus',
        'legal': 'fas fa-gavel',
        
        // Support/Service categories
        'customer service': 'fas fa-headset',
        'support': 'fas fa-headset',
        'technical support': 'fas fa-headset',
        'help desk': 'fas fa-question-circle',
        
        // Engineering categories
        'engineering': 'fas fa-cogs',
        'mechanical': 'fas fa-cogs',
        'civil engineering': 'fas fa-hard-hat',
        'electrical': 'fas fa-bolt',
        'chemical': 'fas fa-flask',
        
        // Healthcare categories
        'healthcare': 'fas fa-heartbeat',
        'medical': 'fas fa-stethoscope',
        'nursing': 'fas fa-user-nurse',
        'pharmacy': 'fas fa-pills',
        
        // Education categories
        'education': 'fas fa-graduation-cap',
        'teaching': 'fas fa-chalkboard-teacher',
        'training': 'fas fa-book-open',
        
        // Other categories
        'logistics': 'fas fa-truck',
        'transportation': 'fas fa-truck',
        'manufacturing': 'fas fa-industry',
        'retail': 'fas fa-store',
        'hospitality': 'fas fa-concierge-bell',
        'food service': 'fas fa-utensils',
        'construction': 'fas fa-hammer',
        'real estate': 'fas fa-home'
    };
    
    // Color classes for icons
    const iconColors = [
        'text-primary', 'text-warning', 'text-success', 'text-info', 
        'text-danger', 'text-dark', 'text-secondary'
    ];
    
    // Get appropriate icon for category
    function getCategoryIcon(categoryName) {
        const name = categoryName.toLowerCase();
        
        // Direct match
        if (categoryIcons[name]) {
            return categoryIcons[name];
        }
        
        // Partial match
        for (const [key, icon] of Object.entries(categoryIcons)) {
            if (name.includes(key) || key.includes(name)) {
                return icon;
            }
        }
        
        // Default icon
        return 'fas fa-briefcase';
    }
    
    // Get random color class
    function getRandomColor(index) {
        return iconColors[index % iconColors.length];
    }
    
    // Format job count
    function formatJobCount(count) {
        if (count === 0) return 'No Jobs';
        if (count === 1) return '1 Job';
        return `${count} Jobs`;
    }
    
    // Load categories and job counts
    async function loadJobCategories() {
        try {
            // Fetch categories and jobs in parallel
            const [categoriesResponse, jobsResponse] = await Promise.all([
                fetch('/api/job-categories'),
                fetch('/api/jobs')
            ]);
            
            if (!categoriesResponse.ok || !jobsResponse.ok) {
                throw new Error('Failed to fetch data');
            }
            
            const categories = await categoriesResponse.json();
            const jobs = await jobsResponse.json();
            
            // Count jobs per category
            const jobCountByCategory = {};
            jobs.forEach(job => {
                const categoryId = job.category_id;
                jobCountByCategory[categoryId] = (jobCountByCategory[categoryId] || 0) + 1;
            });
            
            // Hide loading spinner
            document.getElementById('loadingSpinner').style.display = 'none';
            
            // Generate category cards
            const container = document.getElementById('categoriesContainer');
            
            if (categories.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center">
                        <p class="text-muted">No job categories available at the moment.</p>
                    </div>
                `;
                return;
            }
            
            categories.forEach((category, index) => {
                const jobCount = jobCountByCategory[category.id] || 0;
                const icon = getCategoryIcon(category.name);
                const colorClass = getRandomColor(index);
                
                const categoryCard = `
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card card-hover rounded-4 border-0 shadow-sm p-4 text-center h-100" 
                             style="cursor: pointer;" 
                             onclick="filterJobsByCategory(${category.id}, '${category.name}')">
                            <div class="card-icon mx-auto mb-3">
                                <i class="${icon} fa-2x ${colorClass}"></i>
                            </div>
                            <h5 class="card-title fw-bold">${category.name}</h5>
                            <p class="card-text text-muted">${formatJobCount(jobCount)}</p>
                            <small class="text-muted">${category.slug}</small>
                        </div>
                    </div>
                `;
                
                container.insertAdjacentHTML('beforeend', categoryCard);
            });
            
        } catch (error) {
            console.error('Error loading job categories:', error);
            
            // Hide loading spinner and show error
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('errorContainer').style.display = 'block';
        }
    }
    
    // Filter jobs by category
    function filterJobsByCategory(categoryId, categoryName) {
        // Redirect to jobs page with category filter
        window.location.href = `/jobs?category=${categoryId}`;
    }
    
    // Load categories when DOM is ready
    document.addEventListener('DOMContentLoaded', loadJobCategories);
</script>

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
    
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
</style>
