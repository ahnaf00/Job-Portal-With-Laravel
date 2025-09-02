<?php

namespace Database\Seeders;

use App\Models\AllJob;
use App\Models\Company;
use App\Models\JobCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all companies and job categories
        $companies = Company::all();
        $jobCategories = JobCategory::all();

        if ($companies->isEmpty() || $jobCategories->isEmpty()) {
            $this->command->warn('Please run CompanySeeder and JobCategorySeeder first');
            return;
        }

        $jobTypes = ['full-time', 'part-time', 'remote', 'contract'];
        $locations = [
            'New York, NY', 'San Francisco, CA', 'Los Angeles, CA', 'Chicago, IL', 'Boston, MA',
            'Seattle, WA', 'Austin, TX', 'Denver, CO', 'Remote', 'Miami, FL', 'Atlanta, GA',
            'Washington, DC', 'Portland, OR', 'Phoenix, AZ', 'Dallas, TX'
        ];

        $jobTitles = [
            'Technology' => [
                'Senior Software Engineer', 'Full Stack Developer', 'Frontend Developer', 
                'Backend Developer', 'DevOps Engineer', 'Data Scientist', 'Product Manager',
                'UI/UX Designer', 'Mobile App Developer', 'System Administrator', 'QA Engineer',
                'Cybersecurity Specialist', 'Machine Learning Engineer', 'Cloud Architect'
            ],
            'Marketing' => [
                'Digital Marketing Manager', 'Content Marketing Specialist', 'SEO Specialist',
                'Social Media Manager', 'Marketing Coordinator', 'Brand Manager', 'Growth Hacker',
                'Email Marketing Specialist', 'PPC Specialist', 'Marketing Analyst'
            ],
            'Sales' => [
                'Sales Representative', 'Account Manager', 'Business Development Manager',
                'Sales Director', 'Inside Sales Representative', 'Customer Success Manager',
                'Sales Coordinator', 'Regional Sales Manager', 'Sales Engineer'
            ],
            'Finance' => [
                'Financial Analyst', 'Accountant', 'Finance Manager', 'Investment Analyst',
                'Controller', 'CFO', 'Tax Specialist', 'Budget Analyst', 'Credit Analyst'
            ],
            'Human Resources' => [
                'HR Manager', 'Recruiter', 'HR Coordinator', 'Talent Acquisition Specialist',
                'Benefits Administrator', 'HR Business Partner', 'Compensation Analyst'
            ],
            'Healthcare' => [
                'Registered Nurse', 'Medical Assistant', 'Healthcare Administrator',
                'Physical Therapist', 'Medical Technologist', 'Healthcare Coordinator'
            ],
            'Education' => [
                'Teacher', 'Principal', 'Academic Coordinator', 'Education Consultant',
                'Curriculum Developer', 'Training Manager', 'Educational Technology Specialist'
            ],
            'Customer Service' => [
                'Customer Service Representative', 'Customer Support Specialist',
                'Call Center Agent', 'Technical Support Specialist', 'Customer Experience Manager'
            ]
        ];

        $jobDescriptionTemplates = [
            'technology' => "We are seeking a talented {title} to join our dynamic team. You will be responsible for developing and maintaining high-quality software solutions, collaborating with cross-functional teams, and contributing to our technical architecture decisions.

Key Responsibilities:
• Design, develop, and maintain scalable applications
• Collaborate with product managers and designers
• Write clean, maintainable code following best practices
• Participate in code reviews and technical discussions
• Troubleshoot and debug complex issues
• Stay up-to-date with latest technologies and industry trends

Requirements:
• Bachelor's degree in Computer Science or related field
• 3+ years of experience in software development
• Proficiency in modern programming languages and frameworks
• Strong problem-solving skills and attention to detail
• Experience with version control systems (Git)
• Excellent communication and teamwork skills

Benefits:
• Competitive salary and equity package
• Comprehensive health insurance
• Flexible working hours and remote work options
• Professional development opportunities
• Modern tech stack and equipment",

            'marketing' => "Join our marketing team as a {title} and help drive our brand growth and customer acquisition. You will be responsible for developing and executing marketing strategies, analyzing campaign performance, and collaborating with various teams to achieve business objectives.

Key Responsibilities:
• Develop and implement marketing campaigns across multiple channels
• Analyze marketing metrics and prepare performance reports
• Collaborate with sales and product teams
• Manage marketing budgets and vendor relationships
• Create compelling marketing content and materials
• Stay current with marketing trends and best practices

Requirements:
• Bachelor's degree in Marketing, Business, or related field
• 2+ years of experience in marketing or related role
• Strong analytical and project management skills
• Experience with marketing automation tools
• Excellent written and verbal communication skills
• Creative thinking and problem-solving abilities

Benefits:
• Competitive salary and performance bonuses
• Health and dental insurance
• Professional development budget
• Flexible work arrangements
• Team building events and company culture",

            'sales' => "We are looking for a motivated {title} to join our sales team and drive revenue growth. You will be responsible for building relationships with prospects, closing deals, and contributing to our sales strategy and processes.

Key Responsibilities:
• Generate new leads and build sales pipeline
• Conduct product demonstrations and presentations
• Negotiate contracts and close deals
• Maintain customer relationships and ensure satisfaction
• Collaborate with marketing and customer success teams
• Meet and exceed sales targets and quotas

Requirements:
• Bachelor's degree in Business, Marketing, or related field
• 2+ years of sales experience
• Strong communication and interpersonal skills
• Proven track record of meeting sales targets
• Experience with CRM systems
• Self-motivated and results-oriented

Benefits:
• Base salary plus commission structure
• Health insurance and retirement plans
• Sales incentives and bonuses
• Career advancement opportunities
• Training and development programs",

            'default' => "We are seeking a dedicated {title} to join our growing team. In this role, you will have the opportunity to make a significant impact while working in a collaborative and innovative environment.

Key Responsibilities:
• Execute daily tasks and responsibilities with excellence
• Collaborate effectively with team members and stakeholders
• Contribute to process improvements and best practices
• Maintain high standards of quality and professionalism
• Support company goals and objectives
• Participate in training and development activities

Requirements:
• Relevant education or experience in the field
• Strong work ethic and attention to detail
• Excellent communication and interpersonal skills
• Ability to work independently and as part of a team
• Problem-solving skills and adaptability
• Commitment to continuous learning and improvement

Benefits:
• Competitive compensation package
• Comprehensive benefits including health insurance
• Professional development opportunities
• Positive work environment and company culture
• Work-life balance and flexible arrangements"
        ];

        $this->command->info('Creating sample jobs...');

        foreach ($companies as $company) {
            // Create 2-5 jobs per company
            $jobCount = rand(2, 5);
            
            for ($i = 0; $i < $jobCount; $i++) {
                $category = $jobCategories->random();
                $categoryName = strtolower($category->name);
                
                // Get appropriate job titles for this category
                $availableTitles = $jobTitles[$category->name] ?? $jobTitles['Technology'];
                $title = $availableTitles[array_rand($availableTitles)];
                
                // Get appropriate description template
                $templateKey = 'default';
                if (str_contains($categoryName, 'tech') || str_contains($categoryName, 'it')) {
                    $templateKey = 'technology';
                } elseif (str_contains($categoryName, 'market')) {
                    $templateKey = 'marketing';
                } elseif (str_contains($categoryName, 'sales')) {
                    $templateKey = 'sales';
                }
                
                $description = str_replace('{title}', $title, $jobDescriptionTemplates[$templateKey]);
                
                // Determine salary range based on job level and type
                $salaryMin = rand(35000, 80000);
                $salaryMax = $salaryMin + rand(20000, 50000);
                
                // Higher salaries for senior positions and certain categories
                if (str_contains(strtolower($title), 'senior') || str_contains(strtolower($title), 'manager') || str_contains(strtolower($title), 'director')) {
                    $salaryMin = rand(70000, 120000);
                    $salaryMax = $salaryMin + rand(30000, 80000);
                }

                $isPublished = rand(1, 10) <= 8; // 80% chance of being published
                $isFeatured = $isPublished && rand(1, 10) <= 3; // 30% of published jobs are featured
                
                AllJob::create([
                    'company_id' => $company->id,
                    'category_id' => $category->id,
                    'title' => $title,
                    'slug' => Str::slug($title . '-' . $company->name . '-' . time() . '-' . rand(1000, 9999)),
                    'description' => $description,
                    'location' => $locations[array_rand($locations)],
                    'salary_min' => $salaryMin,
                    'salary_max' => $salaryMax,
                    'job_type' => $jobTypes[array_rand($jobTypes)],
                    'is_published' => $isPublished,
                    'is_featured' => $isFeatured,
                    'created_at' => now()->subDays(rand(1, 90)), // Jobs created within last 90 days
                    'updated_at' => now()->subDays(rand(0, 30)), // Some jobs updated recently
                ]);
            }
        }

        $totalJobs = AllJob::count();
        $this->command->info("Created {$totalJobs} sample jobs successfully!");
        
        // Show some statistics
        $publishedJobs = AllJob::where('is_published', true)->count();
        $draftJobs = AllJob::where('is_published', false)->count();
        $featuredJobs = AllJob::where('is_featured', true)->count();
        
        $this->command->info("Statistics:");
        $this->command->info("- Published jobs: {$publishedJobs}");
        $this->command->info("- Draft jobs: {$draftJobs}");
        $this->command->info("- Featured jobs: {$featuredJobs}");
    }
}