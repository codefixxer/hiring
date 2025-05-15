<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Job;
use App\Models\User;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'employee_id',
        'cv_file',
        'cv_link',
        'cover_letter',
        'selected_skills',
        'expected_salary',
        'available_start_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'selected_skills'      => 'array',
        'available_start_date' => 'date',
    ];

    /**
     * The job this application belongs to.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * The user who made this application.
     */
    public function applicant()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
