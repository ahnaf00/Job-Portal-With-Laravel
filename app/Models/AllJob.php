<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllJob extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
