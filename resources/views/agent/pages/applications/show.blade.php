{{-- resources/views/agent/pages/applications/show.blade.php --}}
@extends('agent.layouts.app')

@section('content')
<div class="container my-4">
  <div class="card shadow-sm">
    {{-- Header with status badge --}}
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">
        <i class="bi bi-eye-fill me-2"></i>
        View Application
      </h4>
      <span class="badge bg-light text-primary fs-6">
        {{ ucfirst($application->status) }}
      </span>
    </div>

    {{-- Body --}}
    <div class="card-body">
      {{-- Two‐column metadata --}}
      <div class="row mb-4">
        <div class="col-md-6">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <strong>Job:</strong> {{ $application->job->job_title }}
            </li>
            <li class="list-group-item">
              <strong>Applicant:</strong> {{ $application->applicant->name }}
            </li>
            <li class="list-group-item">
              <strong>Applied On:</strong> {{ $application->created_at->format('Y-m-d') }}
            </li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <strong>Expected Salary:</strong>
              {{ $application->expected_salary
                 ? '$'.number_format($application->expected_salary,2)
                 : 'N/A' }}
            </li>
            <li class="list-group-item">
              <strong>Available From:</strong>
              {{ $application->available_start_date ?? 'N/A' }}
            </li>
            <li class="list-group-item">
              <strong>CV:</strong>
              @if($application->cv_file)
                <a href="{{ asset('storage/'.$application->cv_file) }}" target="_blank">Download</a>
              @elseif($application->cv_link)
                <a href="{{ $application->cv_link }}" target="_blank">View Link</a>
              @else
                N/A
              @endif
            </li>
          </ul>
        </div>
      </div>

      {{-- Cover Letter --}}
      <div class="mb-4">
        <h5 class="mb-2">
          <i class="bi bi-file-earmark-text-fill me-1"></i>
          Cover Letter
        </h5>
        <p class="ms-3">{{ $application->cover_letter }}</p>
      </div>

      {{-- Job Skills --}}
      @php
        $jobSkills = $application->job->skills
          ? array_map('trim', explode(',', $application->job->skills))
          : [];
        $selected = is_array($application->selected_skills)
          ? $application->selected_skills
          : ($application->selected_skills
              ? array_map('trim', explode(',', $application->selected_skills))
              : []);
      @endphp

      <div class="mb-4">
        <h5 class="mb-2">
          <i class="bi bi-lightbulb-fill me-1"></i>
          Job Skills
        </h5>
        @if(count($jobSkills))
          <div class="d-flex flex-wrap gap-2 ms-3">
            @foreach($jobSkills as $skill)
              @php $picked = in_array($skill, $selected); @endphp
              <span class="badge rounded-pill {{ $picked ? 'bg-success' : 'bg-danger' }}">
                <i class="bi {{ $picked ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                {{ $skill }}
              </span>
            @endforeach
          </div>
        @else
          <p class="text-muted ms-3">No skills listed for this job.</p>
        @endif
      </div>

      {{-- Remarks (if any) --}}
      @if($application->remarks)
        <div class="alert alert-warning">
          <strong>Remarks:</strong> {{ $application->remarks }}
        </div>
      @endif
    </div>

    {{-- resources/views/agent/pages/applications/show.blade.php --}}
 
 
  {{-- Footer with “Reject” button that opens modal --}}
  <div class="card-footer text-end">
    <a href="{{ route('agent.applications.index') }}" class="btn btn-secondary me-2">
      <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>

    <form action="{{ route('agent.applications.approve',   $application->id) }}"
          method="POST" class="d-inline me-1">
      @csrf
      @method('PATCH')
      <button class="btn btn-success">
        <i class="bi bi-check-circle-fill me-1"></i> Approve Application
      </button>
    </form>

    <!-- single “Reject” button, opens the modal -->
    <button type="button"
            class="btn btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#rejectModal">
      <i class="bi bi-x-circle-fill me-1"></i> Reject Application
    </button>
  </div>
</div>

{{-- Reject Modal (only one <form> here!) --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="{{ route('agent.applications.reject', $application->id) }}">
      @csrf
      @method('PATCH')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="rejectModalLabel">
            <i class="bi bi-exclamation-circle-fill me-1"></i>Reject Application
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea id="remarks"
                      name="remarks"
                      class="form-control"
                      rows="3"
                      required
                      placeholder="Enter rejection remarks"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-danger">
            Reject
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

