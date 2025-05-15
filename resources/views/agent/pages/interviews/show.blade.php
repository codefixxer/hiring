@extends('agent.layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4>{{ $interview->application->job->job_title ?? 'N/A' }}</h4>
        </div>
        <div class="card-body">
            <p class="mb-3"><strong>Job Description:</strong></p>
            <p>{{ $interview->application->job->job_description ?? 'N/A' }}</p>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Preferred Gender:</strong> {{ ucfirst($interview->application->job->preferred_gender ?? 'N/A') }}</p>
                    <p><strong>Minimum Experience:</strong> {{ $interview->application->job->minimum_experience ?? 0 }} years</p>
                    <p><strong>Education Level:</strong> {{ ucfirst($interview->application->job->education_level ?? 'N/A') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Languages:</strong> {{ $interview->application->job->languages ?? 'N/A' }}</p>
                    <p><strong>Certifications:</strong> {{ $interview->application->job->certifications ?: 'N/A' }}</p>
                    <p><strong>Working Hours/Week:</strong> {{ $interview->application->job->working_hours ?? 'N/A' }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Posting Date:</strong> {{ \Carbon\Carbon::parse($interview->application->job->posting_date)->format('Y-m-d') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Closing Date:</strong> {{ \Carbon\Carbon::parse($interview->application->job->closing_date)->format('Y-m-d') }}</p>
                </div>
            </div>
            <p><strong>Status:</strong> {{ ucfirst($interview->application->job->status ?? 'N/A') }}</p>
            <p><strong>Salary Range:</strong> ${{ number_format($interview->application->job->min_salary, 2) }} - ${{ number_format($interview->application->job->max_salary, 2) }}</p>
            <hr>
            <h5>Required Skills:</h5>
            @if($interview->application->job->skills->isNotEmpty())
                <div>
                    @foreach($interview->application->job->skills as $skill)
                        <span class="badge bg-secondary me-1" style="font-size: 1rem;">{{ $skill->skill_name }}</span>
                    @endforeach
                </div>
            @else
                <p>No skills specified.</p>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('agent.jobs.edit', $interview->application->job->id) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('agent.jobs.index') }}" class="btn btn-secondary">Back to List</a>
            <form action="{{ route('agent.jobs.destroy', $interview->application->job->id) }}" method="POST" class="d-inline-block float-end">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this job?');">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
