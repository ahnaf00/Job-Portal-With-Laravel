<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create some additional users for companies if they don't exist
        $companyOwners = [
            [
                'name' => 'Tech Innovators Ltd',
                'email' => 'owner@techinnovators.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Digital Solutions Inc',
                'email' => 'admin@digitalsolutions.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Creative Minds Agency',
                'email' => 'ceo@creativeminds.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Global Tech Corp',
                'email' => 'hr@globaltech.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'StartUp Ventures',
                'email' => 'contact@startupventures.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
        ];

        $users = [];
        foreach ($companyOwners as $ownerData) {
            $user = User::firstOrCreate(
                ['email' => $ownerData['email']],
                $ownerData
            );
            $users[] = $user;
        }

        // Create companies
        $companies = [
            [
                'name' => 'Tech Innovators Ltd',
                'description' => 'Leading technology company specializing in AI and machine learning solutions. We develop cutting-edge software for enterprise clients and help businesses transform digitally.',
                'website' => 'https://www.techinnovators.com',
                'address' => '123 Tech Street, Silicon Valley, CA 94105',
                'is_verified' => true,
                'verification_document' => 'business_license_tech_innovators.pdf',
            ],
            [
                'name' => 'Digital Solutions Inc',
                'description' => 'Full-service digital agency providing web development, mobile apps, and digital marketing services. We work with startups and Fortune 500 companies.',
                'website' => 'https://www.digitalsolutions.com',
                'address' => '456 Innovation Ave, New York, NY 10001',
                'is_verified' => true,
                'verification_document' => 'incorporation_certificate_digital.pdf',
            ],
            [
                'name' => 'Creative Minds Agency',
                'description' => 'Award-winning creative agency specializing in branding, graphic design, and digital experiences. We help brands tell their story through compelling visual content.',
                'website' => 'https://www.creativeminds.com',
                'address' => '789 Design Boulevard, Los Angeles, CA 90210',
                'is_verified' => false,
                'verification_document' => null,
            ],
            [
                'name' => 'Global Tech Corp',
                'description' => 'Multinational technology corporation providing enterprise software solutions, cloud services, and IT consulting to businesses worldwide.',
                'website' => 'https://www.globaltech.com',
                'address' => '321 Corporate Plaza, Seattle, WA 98101',
                'is_verified' => false,
                'verification_document' => 'company_registration_global.pdf',
            ],
            [
                'name' => 'StartUp Ventures',
                'description' => 'Investment and incubation company focused on early-stage technology startups. We provide funding, mentorship, and resources to help entrepreneurs succeed.',
                'website' => 'https://www.startupventures.com',
                'address' => '654 Venture Capital Way, Austin, TX 73301',
                'is_verified' => false,
                'verification_document' => 'business_plan_startup.pdf',
            ],
            [
                'name' => 'FinTech Solutions',
                'description' => 'Revolutionary financial technology company developing blockchain-based payment systems and cryptocurrency trading platforms.',
                'website' => 'https://www.fintechsolutions.com',
                'address' => '987 Blockchain Street, Miami, FL 33101',
                'is_verified' => true,
                'verification_document' => 'fintech_license_certificate.pdf',
            ],
            [
                'name' => 'HealthCare Innovations',
                'description' => 'Medical technology company developing telemedicine platforms and AI-powered diagnostic tools to improve healthcare accessibility.',
                'website' => 'https://www.healthcareinnovations.com',
                'address' => '147 Medical Center Drive, Boston, MA 02101',
                'is_verified' => false,
                'verification_document' => null,
            ],
            [
                'name' => 'EcoGreen Technologies',
                'description' => 'Sustainable technology company focused on renewable energy solutions and environmental monitoring systems for smart cities.',
                'website' => 'https://www.ecogreen.com',
                'address' => '258 Green Energy Blvd, Portland, OR 97201',
                'is_verified' => true,
                'verification_document' => 'environmental_certification.pdf',
            ],
            [
                'name' => 'Gaming Studios Pro',
                'description' => 'Independent game development studio creating immersive gaming experiences for PC, console, and mobile platforms.',
                'website' => 'https://www.gamingstudios.com',
                'address' => '369 Gaming Street, San Francisco, CA 94102',
                'is_verified' => false,
                'verification_document' => 'game_developer_license.pdf',
            ],
            [
                'name' => 'Data Analytics Corp',
                'description' => 'Data science and analytics company helping businesses make data-driven decisions through advanced analytics and visualization tools.',
                'website' => 'https://www.dataanalytics.com',
                'address' => '741 Data Drive, Chicago, IL 60601',
                'is_verified' => false,
                'verification_document' => null,
            ],
        ];

        foreach ($companies as $index => $companyData) {
            // Assign user to company (cycle through users if more companies than users)
            $user = $users[$index % count($users)];
            
            $companyData['user_id'] = $user->id;
            $companyData['slug'] = Str::slug($companyData['name']);
            
            Company::firstOrCreate(
                ['name' => $companyData['name']],
                $companyData
            );
        }
    }
}