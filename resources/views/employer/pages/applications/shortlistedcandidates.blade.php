@extends('employer.layouts.app')

@section('content')
    <h6 class="mb-0 text-uppercase">Finalize Candidates</h6>
    <hr>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

                <table id="finalizeTable" class="table table-striped table-bordered w-100 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Applicant</th>
                            <th>Job Title</th>
                            <th>Applied On</th>
                            <th>Expected Salary</th>
                            <th>Available From</th>
                            <th>CV</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $app)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $app->applicant->name }}</td>
                                <td>{{ $app->job->job_title }}</td>
                                <td>{{ $app->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if ($app->expected_salary)
                                        ${{ number_format($app->expected_salary, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $app->available_start_date ?? 'N/A' }}</td>
                                <td>
                                    @if ($app->cv_file)
                                        <a href="{{ asset('storage/' . $app->cv_file) }}" target="_blank">Download</a>
                                    @elseif($app->cv_link)
                                        <a href="{{ $app->cv_link }}" target="_blank">View Link</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ ucfirst($app->status) }}</td>
                                <td class="text-center">
                                    <!-- Confirm & Schedule Interview trigger -->
                                    <button type="button" class="btn btn-sm btn-success me-1" data-bs-toggle="modal"
                                        data-bs-target="#scheduleModal-{{ $app->id }}">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        Confirm & Schedule
                                    </button>

                                    <!-- Reject Candidate -->
                                    <form action="{{ route('employer.applications.rejectCandidate', $app->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-x-circle-fill me-1"></i>
                                            Reject
                                        </button>
                                    </form>

                                    <!-- Schedule & Confirm Modal -->
                                    <div class="modal fade" id="scheduleModal-{{ $app->id }}" tabindex="-1"
                                        aria-labelledby="scheduleModalLabel-{{ $app->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="scheduleModalLabel-{{ $app->id }}">
                                                        <i class="bi bi-calendar-event-fill me-1"></i>
                                                        Schedule Interview & Confirm
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <form action="{{ route('employer.interviews.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="application_id"
                                                        value="{{ $app->id }}">

                                                    <div class="modal-body text-start">
                                                        <div class="mb-3">
                                                            <label for="interview_start_{{ $app->id }}"
                                                                class="form-label">
                                                                Interview Start (Date & Time)
                                                            </label>
                                                            <input type="datetime-local" name="interview_start"
                                                                id="interview_start_{{ $app->id }}"
                                                                class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="interview_end_{{ $app->id }}"
                                                                class="form-label">
                                                                Interview End (Date & Time)
                                                            </label>
                                                            <input type="datetime-local" name="interview_end"
                                                                id="interview_end_{{ $app->id }}" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="interview_link_{{ $app->id }}"
                                                                class="form-label">
                                                                Meeting Link
                                                            </label>
                                                            <input type="url" name="interview_link"
                                                                id="interview_link_{{ $app->id }}"
                                                                class="form-control" placeholder="https://..." required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="interview_remarks_{{ $app->id }}"
                                                                class="form-label">
                                                                Remarks (optional)
                                                            </label>
                                                            <textarea name="interview_remarks" id="interview_remarks_{{ $app->id }}" class="form-control" rows="2"
                                                                placeholder="Any remarks..."></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-send-check me-1"></i>
                                                            Schedule & Accept
                                                        </button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
