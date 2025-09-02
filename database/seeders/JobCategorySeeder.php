<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Software Development',
            'Web Development',
            'Mobile Development',
            'Data Science',
            'Machine Learning',
            'Cybersecurity',
            'DevOps',
            'UI/UX Design',
            'Graphic Design',
            'Digital Marketing',
            'Sales',
            'Project Management',
            'Customer Support',
            'Human Resources',
            'Finance',
            'Engineering',
            'Healthcare',
            'Education',
            'Photography',
            'Content Writing'
        ];

        foreach ($categories as $categoryName) {
            JobCategory::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);
        }
    }
}