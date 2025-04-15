<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'interview_start',
        'interview_end',
        'interview_link',
        'status',
        'remarks'
    ];

    public function application()
    {
        return $this->belongsTo(\App\Models\Application::class);
    }
}
