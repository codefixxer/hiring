@extends('user.layouts.app')

@section('title', 'Interview Details')

@section('content')
<div class="container my-5">
    <div class="card border-primary shadow-sm">
        <div class="card-header" style="background-color: #041930; color: #e2ae76;">
            <h4 class="mb-0"><i class="bi bi-calendar-event me-2"></i> Interview Details</h4>
        </div>
        <div class="card-body">
            <h5 class="mb-3 text-primary">Interview Information</h5>
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td>{{ $interview->id }}</td>
                </tr>
                <tr>
                    <th>Job Title</th>
                    <td>{{ $interview->application->job->job_title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Applicant Name</th>
                    <td>{{ $interview->application->applicant->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Start Time</th>
                    <td>{{ \Carbon\Carbon::parse($interview->interview_start)->format('Y-m-d H:i') }}</td>
                </tr>
                <tr>
                    <th>End Time</th>
                    <td>{{ \Carbon\Carbon::parse($interview->interview_end)->format('Y-m-d H:i') }}</td>
                </tr>
                <tr>
                    <th>Meeting Link</th>
                    <td>
                        <a href="{{ $interview->interview_link }}" target="_blank">
                            {{ $interview->interview_link }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-{{ $interview->status == 'accepted' ? 'success' : ($interview->status == 'rejected' ? 'danger' : ($interview->status == 'postponed' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($interview->status) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Remarks</th>
                    <td>{{ $interview->remarks ?? 'N/A' }}</td>
                </tr>
            </table>

            <a href="{{ route('agent.interviews.index') }}" class="btn btn-secondary mt-3">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to Interviews
            </a>
        </div>
    </div>
</div>
@endsection
