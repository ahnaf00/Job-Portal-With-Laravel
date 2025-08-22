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
}
