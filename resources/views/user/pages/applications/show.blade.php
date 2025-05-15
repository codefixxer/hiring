@extends('user.layouts.app')

@section('content')
<div class="container my-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">
        <i class="bi bi-briefcase-fill me-2"></i>
        Application for: {{ $application->job->job_title }}
      </h4>
    </div>

    <div class="card-body p-4">
      {{-- Cover Letter --}}
      <div class="mb-3">
        <h5 class="fw-bold"><i class="bi bi-file-earmark-text me-1"></i> Cover Letter</h5>
        <p>{{ $application->cover_letter }}</p>
      </div>
      <hr>

      {{-- CV & Salary/Start Date --}}
      <div class="row mb-3">
        <div class="col-md-6">
          <h5 class="fw-bold"><i class="bi bi-file-earmark-person me-1"></i> CV</h5>
          @if($application->cv_file)
            <p><a href="{{ asset('storage/'.$application->cv_file) }}" target="_blank">Download CV</a></p>
          @elseif($application->cv_link)
            <p><a href="{{ $application->cv_link }}" target="_blank">View CV Link</a></p>
          @else
            <p class="text-muted">N/A</p>
          @endif
        </div>
        <div class="col-md-6">
          <h5 class="fw-bold"><i class="bi bi-cash-coin me-1"></i> Salary & Start Date</h5>
          <p>
            <strong>Expected Salary:</strong>
            {{ $application->expected_salary
               ? '$'.number_format($application->expected_salary,2)
               : 'N/A' }}
          </p>
          <p>
            <strong>Available From:</strong>
            {{ $application->available_start_date ?? 'N/A' }}
          </p>
        </div>
      </div>
      <hr>

      {{-- Job Skills --}}
      @php
        $jobSkills = $application->job->skills
          ? array_map('trim', explode(',', $application->job->skills))
          : [];
      @endphp

    @php
    // explode the job’s comma-separated skills into an array
    $jobSkills = $application->job->skills
        ? array_map('trim', explode(',', $application->job->skills))
        : [];

    // normalize selected_skills into an array of names
    $selected = is_array($application->selected_skills)
        ? $application->selected_skills
        : ($application->selected_skills
            ? array_map('trim', explode(',', $application->selected_skills))
            : []);
@endphp

<div class="mb-3">
    <h5 class="fw-bold">
        <i class="bi bi-lightbulb-fill me-1"></i>
        Job Skills
    </h5>

    @if(count($jobSkills))
        <div class="d-flex flex-wrap gap-2">
            @foreach($jobSkills as $skill)
                @php $isSelected = in_array($skill, $selected); @endphp

                <span class="badge rounded-pill {{ $isSelected ? 'bg-success' : 'bg-danger' }}">
                    <i class="bi {{ $isSelected ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                    {{ $skill }}
                </span>
            @endforeach
        </div>
    @else
        <p class="text-muted">No skills listed for this job.</p>
    @endif
</div>

      <hr>

      {{-- Status & Remarks --}}
      <div class="mb-3">
        <h5 class="fw-bold"><i class="bi bi-card-checklist me-1"></i> Application Status</h5>
        <p>
          <span class="badge 
            {{ $application->status === 'approved' ? 'bg-success' 
             : ($application->status === 'rejected' ? 'bg-danger' 
             : 'bg-secondary') }}">
            {{ ucfirst($application->status) }}
          </span>
        </p>
        @if($application->remarks)
          <p><strong>Remarks:</strong> {{ $application->remarks }}</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
