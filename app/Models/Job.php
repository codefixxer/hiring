<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'job_title',
        'job_description',
        'preferred_gender',
        'minimum_experience',
        'education_level',
        'languages',
        'certifications',
        'min_salary',
        'max_salary',
        'working_hours',
        'posting_date',
        'closing_date',
        'status'
    ];

    public function skills()
    {
        return $this->hasMany(JobSkill::class);
    }

    
}
