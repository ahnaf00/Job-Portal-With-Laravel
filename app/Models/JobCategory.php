<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $guarded = ['id'];

    public function allJobs()
    {
        return $this->hasMany(AllJob::class,'category_id');
    }

    // /**
    //  * Scope to get categories with job counts
    //  * @param $query
    //  * @param bool|null $isPublished - true for published jobs, false for unpublished, null for all jobs
    //  */
    // public function scopeWithJobCounts($query, $isPublished = true)
    // {
    //     return $query->withCount(['allJobs' => function($q) use ($isPublished) {
    //         if ($isPublished !== null) {
    //             $q->where('is_published', $isPublished);
    //         }
    //     }]);
    // }

    /**
     * Scope to get categories with job counts
     *
     * @param $query
     * @param bool|null $isPublished - true for published jobs, false for unpublished, null for all jobs
     */
    public function scopeWithJobCounts($query, $isPublished = true)
    {
        return $query->withCount(['allJobs as jobs_count' => function($q) use ($isPublished) {
            if ($isPublished !== null) {
                $q->where('is_published', $isPublished);
            }
        }]);
    }


    /**
     * Get appropriate icon for the category
     */
    public function getCategoryIcon()
    {
        $icons = [
            'software development' => 'fas fa-code',
            'web development' => 'fas fa-globe',
            'mobile development' => 'fas fa-mobile-alt',
            'data science' => 'fas fa-chart-line',
            'machine learning' => 'fas fa-robot',
            'cybersecurity' => 'fas fa-shield-alt',
            'devops' => 'fas fa-server',
            'ui/ux design' => 'fas fa-paint-brush',
            'graphic design' => 'fas fa-palette',
            'digital marketing' => 'fas fa-bullhorn',
            'sales' => 'fas fa-handshake',
            'project management' => 'fas fa-tasks',
            'customer support' => 'fas fa-headset',
            'human resources' => 'fas fa-users',
            'finance' => 'fas fa-calculator',
            'engineering' => 'fas fa-cogs',
            'healthcare' => 'fas fa-heartbeat',
            'education' => 'fas fa-graduation-cap',
            'photography' => 'fas fa-camera',
            'content writing' => 'fas fa-pen'
        ];

        return $icons[strtolower($this->name)] ?? 'fas fa-briefcase';
    }
}
