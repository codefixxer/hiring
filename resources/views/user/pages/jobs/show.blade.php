@extends('user.layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4>{{ $job->job_title }}</h4>
        </div>
        <div class="card-body">
            <p class="mb-3"><strong>Job Description:</strong></p>
            <p>{{ $job->job_description }}</p>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Preferred Gender:</strong> {{ ucfirst($job->preferred_gender) }}</p>
                    <p><strong>Minimum Experience:</strong> {{ $job->minimum_experience }} years</p>
                    <p><strong>Education Level:</strong> {{ ucfirst($job->education_level) }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Languages:</strong> {{ $job->languages }}</p>
                    <p><strong>Certifications:</strong> {{ $job->certifications ?: 'N/A' }}</p>
                    <p><strong>Working Hours/Week:</strong> {{ $job->working_hours }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Posting Date:</strong> {{ \Carbon\Carbon::parse($job->posting_date)->format('Y-m-d') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Closing Date:</strong> {{ \Carbon\Carbon::parse($job->closing_date)->format('Y-m-d') }}</p>
                </div>
            </div>
            <p><strong>Status:</strong> {{ ucfirst($job->status) }}</p>
            <p><strong>Salary Range:</strong> ${{ number_format($job->min_salary,2) }} - ${{ number_format($job->max_salary,2) }}</p>
            <hr>

            <h5>Required Skills:</h5>
            @php
                $skills = $job->skills ? explode(',', $job->skills) : [];
            @endphp

            @if(count($skills))
                <div>
                    @foreach($skills as $skill)
                        <span class="badge bg-secondary me-1" style="font-size: 1rem;">
                            {{ ucfirst(trim($skill)) }}
                        </span>
                    @endforeach
                </div>
            @else
                <p>No skills specified.</p>
            @endif
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('user.jobs.index') }}" class="btn btn-secondary">Back to List</a>

            @auth
                @if(auth()->user()->role === 'user')
                   <a href="{{ route('user.applications.create', ['job_id' => $job->id]) }}"
   class="btn btn-success">Apply</a>

                @endif
            @endauth
        </div>
    </div>
</div>
@endsection
