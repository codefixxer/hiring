<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'status',
        'skills',
        'agent_ids',
        'remarks',            
    ];

    protected $casts = [
        'agent_ids'    => 'array',
        'posting_date' => 'date',
        'closing_date' => 'date',
    ];

    /**
     * The employer who owns this job.
     */
    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    /**
     * The agents assigned to this job.
     */
    public function agents()
    {
        return User::whereIn('id', $this->agent_ids ?? [])->get();
    }



    
}
