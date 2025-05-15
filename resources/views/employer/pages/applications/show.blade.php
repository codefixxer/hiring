@extends('employer.layouts.app')

@section('content')
<!-- Include Bootstrap Icons CSS if not already included -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container my-4">
    <div class="card shadow-sm">
        <!-- Header with Gradient Background -->
        <div class="card-header text-white" style="background: linear-gradient(to right, #28a745, #218838);">
            <h4 class="mb-0">
                <i class="bi bi-briefcase-fill me-2"></i> 
                Application for: {{ $application->job->job_title }}
            </h4>
        </div>
        <div class="card-body p-4">
            <!-- Cover Letter -->
            <div class="mb-3">
                <h5 class="fw-bold mb-2"><i class="bi bi-file-earmark-text me-1"></i> Cover Letter</h5>
                <p class="mb-0">{{ $application->cover_letter }}</p>
            </div>
            <hr>
            <!-- CV and Salary/Start Date -->
            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="fw-bold mb-2"><i class="bi bi-file-earmark-person me-1"></i> CV</h5>
                    @if($application->cv_file)
                        <a href="{{ asset('storage/'.$application->cv_file) }}" target="_blank" class="text-decoration-none">
                            <i class="bi bi-file-earmark-arrow-down me-1"></i> View Uploaded CV
                        </a>
                    @elseif($application->cv_link)
                        <a href="{{ $application->cv_link }}" target="_blank" class="text-decoration-none">
                            <i class="bi bi-link-45deg me-1"></i> View CV Link
                        </a>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-2"><i class="bi bi-cash-coin me-1"></i> Salary & Dates</h5>
                    <p class="mb-1"><strong>Expected Salary:</strong>
                        @if($application->expected_salary)
                            ${{ number_format($application->expected_salary,2) }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </p>
                    <p class="mb-0"><strong>Available Start Date:</strong> {{ $application->available_start_date ?? 'N/A' }}</p>
                </div>
            </div>
            <hr>
            <!-- Job Skills -->
            <div class="mb-3">
                <h5 class="fw-bold mb-3"><i class="bi bi-lightbulb-fill me-1"></i> Job Skills</h5>
                @if($application->job->skills->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($application->job->skills as $skill)
                            @php
                                $isSelected = is_array($application->selected_skills) 
                                              && in_array($skill->id, $application->selected_skills);
                            @endphp
                            @if ($isSelected)
                                <span class="badge rounded-pill bg-success px-3 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i> {{ $skill->skill_name }}
                                </span>
                            @else
                                <span class="badge rounded-pill bg-danger px-3 py-2">
                                    <i class="bi bi-x-circle-fill me-1"></i> {{ $skill->skill_name }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="mb-0 text-muted">No skills defined for this job.</p>
                @endif
            </div>
            <hr>
            <!-- Status & Remarks -->
            <div class="mb-3">
                <h5 class="fw-bold mb-2"><i class="bi bi-card-checklist me-1"></i> Status</h5>
                <p class="mb-1">
                    <strong>Current:</strong>
                    <span class="badge 
                        @if($application->status === 'approved') bg-success 
                        @elseif($application->status === 'rejected') bg-danger 
                        @else bg-secondary 
                        @endif">
                        {{ ucfirst($application->status) }}
                    </span>
                </p>
                @if($application->remarks)
                    <p class="mb-0"><strong>Remarks:</strong> {{ $application->remarks }}</p>
                @endif
            </div>
        </div>
        <!-- Footer with Action Buttons -->
        <div class="card-footer d-flex flex-wrap gap-2">
            <a href="{{ route('employer.applications.edit', $application->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i> Edit Application
            </a>
            <a href="{{ route('employer.applications.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to My Applications
            </a>
            <form action="{{ route('employer.applications.destroy', $application->id) }}" method="POST" class="d-inline-block ms-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this application?');">
                    <i class="bi bi-trash-fill me-1"></i> Delete Application
                </button>
            </form>
            <!-- New Approve Button triggers Approve Modal -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                <i class="bi bi-check2-circle me-1"></i> Approve
            </button>
            <!-- Reject Button triggers Reject Modal -->
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="bi bi-exclamation-triangle me-1"></i> Reject
            </button>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form id="approveInterviewForm" method="POST" action="{{ route('employer.interviews.store') }}">
        @csrf
        <!-- Hidden: application_id -->
        <input type="hidden" name="application_id" value="{{ $application->id }}">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="approveModalLabel">
                <i class="bi bi-check-circle-fill me-1"></i> Approve & Schedule Interview
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Interview Start Date/Time -->
            <div class="mb-3">
              <label for="interviewStart" class="form-label">Interview Start (Date & Time)</label>
              <input type="datetime-local" class="form-control" id="interviewStart" name="interview_start" required>
            </div>
            <!-- Interview End Date/Time -->
            <div class="mb-3">
              <label for="interviewEnd" class="form-label">Interview End (Date & Time)</label>
              <input type="datetime-local" class="form-control" id="interviewEnd" name="interview_end" required>
            </div>
            <!-- Interview Link -->
            <div class="mb-3">
              <label for="interviewLink" class="form-label">Interview Link</label>
              <input type="url" class="form-control" id="interviewLink" name="interview_link" placeholder="Enter meeting URL" required>
            </div>
            <!-- Interview Outcome -->
            <div class="mb-3">
              <label for="interviewStatus" class="form-label">Interview Outcome</label>
              <select class="form-select" id="interviewStatus" name="interview_status" required>
                <option value="accepted" selected>Accepted</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>
            <!-- Interview Remarks (display if outcome is rejected) -->
            <div class="mb-3" id="interviewRemarksGroup" style="display: none;">
              <label for="interviewRemarks" class="form-label">Interview Remarks</label>
              <input type="text" class="form-control" id="interviewRemarks" name="interview_remarks" placeholder="Enter remarks if rejected">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-send-check me-1"></i> Submit
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  

<!-- Reject Modal (For Application Rejection) -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="rejectForm" method="POST" action="{{ route('employer.applications.reject', $application->id) }}">
      @csrf
      @method('PATCH')
      <input type="hidden" name="status" value="rejected">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rejectModalLabel">
              <i class="bi bi-exclamation-diamond-fill me-1"></i> Reject Application
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Enter remarks for rejection" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
          <button type="submit" class="btn btn-warning">
              <i class="bi bi-send-exclamation me-1"></i> Submit Rejection
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('interviewStatus').addEventListener('change', function() {
        if (this.value === 'rejected') {
            document.getElementById('interviewRemarksGroup').style.display = 'block';
            document.getElementById('interviewRemarks').required = true;
        } else {
            document.getElementById('interviewRemarksGroup').style.display = 'none';
            document.getElementById('interviewRemarks').required = false;
        }
    });
</script>
@endsection
