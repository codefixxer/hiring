@extends('agent.layouts.app')

@section('content')
<style>
    .text-muted, .nav-link.active{color: silver !important}
</style>
<div class="container my-4">
    <h5 class="mb-3">Interview Overview</h5>
    
    <!-- Nav Tabs with redirection using query parameters -->
    <ul class="nav nav-tabs" id="interviewTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab == 'pending' ? 'active' : '' }}" 
               href="{{ route('agent.interviews.index', ['tab' => 'pending']) }}">
               Pending
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab == 'accepted' ? 'active' : '' }}" 
               href="{{ route('agent.interviews.index', ['tab' => 'accepted']) }}">
               Accepted
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab == 'postponed' ? 'active' : '' }}" 
               href="{{ route('agent.interviews.index', ['tab' => 'postponed']) }}">
               Postponed
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab == 'rejected' ? 'active' : '' }}" 
               href="{{ route('agent.interviews.index', ['tab' => 'rejected']) }}">
               Rejected
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="interviewTabsContent">
        <!-- PENDING TAB -->
        <div class="tab-pane fade {{ $activeTab == 'pending' ? 'show active' : '' }}" id="pending" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="pendingTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Job Title</th>
                            <th>Interview Start</th>
                            <th>Interview End</th>
                            <th>Interview Link</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingInterviews as $interview)
                        <tr>
                            <td>{{ $interview->id }}</td>
                            <td>{{ $interview->application->job->job_title }}</td>
                            <td>
                                @php
                                    $start = \Carbon\Carbon::parse($interview->interview_start);
                                    $now = \Carbon\Carbon::now();
                                    $diff = $now->diff($start);
                                @endphp
                            
                                @if ($start->isToday())
                                    Today at {{ $start->format('g:i A') }}
                                @elseif ($start->isTomorrow())
                                    Tomorrow at {{ $start->format('g:i A') }}
                                @elseif ($start->isPast())
                                    {{ $start->diffForHumans() }} (ago)
                                @else
                                    After 
                                    {{ $diff->d > 0 ? $diff->d . ' days' : '' }}
                                    {{ $diff->h > 0 ? ($diff->d > 0 ? ' and ' : '') . $diff->h . ' hours' : '' }}
                                @endif
                            </td>
                            
                            <td>
                                @php
                                    $end = \Carbon\Carbon::parse($interview->interview_end);
                                    $diffEnd = $now->diff($end);
                                @endphp
                            
                                @if ($end->isToday())
                                    Today at {{ $end->format('g:i A') }}
                                @elseif ($end->isTomorrow())
                                    Tomorrow at {{ $end->format('g:i A') }}
                                @elseif ($end->isPast())
                                    {{ $end->diffForHumans() }} (ago)
                                @else
                                    After 
                                    {{ $diffEnd->d > 0 ? $diffEnd->d . ' days' : '' }}
                                    {{ $diffEnd->h > 0 ? ($diffEnd->d > 0 ? ' and ' : '') . $diffEnd->h . ' hours' : '' }}
                                @endif
                            </td>
                             <td>
                                <a href="{{ $interview->interview_link }}" target="_blank">
                                    {{ $interview->interview_link }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($interview->status) }}</span>
                            </td>
                            <td>{{ $interview->remarks ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $current = \Carbon\Carbon::now();
                                    $start = \Carbon\Carbon::parse($interview->interview_start);
                                    $end   = \Carbon\Carbon::parse($interview->interview_end);
                                @endphp

                                @if($current->lt($start))
                                    <span class="text-muted">Waiting to start</span>
                                @elseif($current->between($start, $end))
                                    <a href="{{ $interview->interview_link }}" target="_blank" class="btn btn-sm btn-success">
                                        <i class="bi bi-camera-video"></i> Join Meeting
                                    </a>
                                @elseif($current->gte($end))
                                    <!-- Accept Button -->
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#acceptModal{{$interview->id}}">
                                        <i class="bi bi-check2-circle"></i> Accept
                                    </button>
                                    <!-- Reject Button -->
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#rejectModal{{$interview->id}}">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                    <!-- Postpone Button -->
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#postponeModal{{$interview->id}}">
                                        <i class="bi bi-clock-history"></i> Postpone
                                    </button>
                                @endif
                            </td>
                        </tr>
                        
                        <!-- Accept Modal for Interview ID {{$interview->id}} -->
                        <div class="modal fade" id="acceptModal{{$interview->id}}" tabindex="-1" aria-labelledby="acceptModalLabel{{$interview->id}}" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" action="{{ route('agent.interviews.update', $interview->id) }}">
                              @csrf
                              @method('PATCH')
                              <input type="hidden" name="status" value="accepted">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="acceptModalLabel{{$interview->id}}">
                                      <i class="bi bi-check2-circle me-1"></i> Accept Interview
                                  </h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <p>Are you sure you want to mark this interview as <strong>accepted</strong>?</p>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                      <i class="bi bi-x-circle me-1"></i> Cancel
                                  </button>
                                  <button type="submit" class="btn btn-success">
                                      <i class="bi bi-check2-circle me-1"></i> Confirm
                                  </button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                        
                        <!-- Reject Modal for Interview ID {{$interview->id}} -->
                        <div class="modal fade" id="rejectModal{{$interview->id}}" tabindex="-1" aria-labelledby="rejectModalLabel{{$interview->id}}" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" action="{{ route('agent.interviews.update', $interview->id) }}">
                              @csrf
                              @method('PATCH')
                              <input type="hidden" name="status" value="rejected">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="rejectModalLabel{{$interview->id}}">
                                      <i class="bi bi-x-circle me-1"></i> Reject Interview
                                  </h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label for="remarks{{$interview->id}}" class="form-label">Remarks</label>
                                    <input type="text" class="form-control" id="remarks{{$interview->id}}" name="remarks" placeholder="Enter rejection remarks" required>
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
                        
                        <!-- Postpone Modal for Interview ID {{$interview->id}} -->
                        <div class="modal fade" id="postponeModal{{$interview->id}}" tabindex="-1" aria-labelledby="postponeModalLabel{{$interview->id}}" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" action="{{ route('agent.interviews.update', $interview->id) }}">
                              @csrf
                              @method('PATCH')
                              <input type="hidden" name="status" value="postponed">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="postponeModalLabel{{$interview->id}}">
                                      <i class="bi bi-clock-history me-1"></i> Postpone Interview
                                  </h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label for="newStart{{$interview->id}}" class="form-label">New Interview Start</label>
                                    <input type="datetime-local" class="form-control" id="newStart{{$interview->id}}" name="interview_start" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="newEnd{{$interview->id}}" class="form-label">New Interview End</label>
                                    <input type="datetime-local" class="form-control" id="newEnd{{$interview->id}}" name="interview_end" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="newLink{{$interview->id}}" class="form-label">New Interview Link</label>
                                    <input type="url" class="form-control" id="newLink{{$interview->id}}" name="interview_link" placeholder="Enter new meeting URL" required>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                      <i class="bi bi-x-circle me-1"></i> Cancel
                                  </button>
                                  <button type="submit" class="btn btn-info">
                                      <i class="bi bi-clock-history me-1"></i> Submit Postponement
                                  </button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                        
                        <!-- End of Row Modals for this interview -->
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Accepted Tab Pane -->
        <div class="tab-pane fade {{ $activeTab == 'accepted' ? 'show active' : '' }}" id="accepted" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="acceptedTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Job Title</th>
                            <th>Interview Start</th>
                            <th>Interview End</th>
                            <th>Interview Link</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($acceptedInterviews as $interview)
                        <tr>
                            <td>{{ $interview->id }}</td>
                            <td>{{ $interview->application->job->job_title }}</td>
                            <td>{{ \Carbon\Carbon::parse($interview->interview_start)->format('Y-m-d H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($interview->interview_end)->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ $interview->interview_link }}" target="_blank">
                                    {{ $interview->interview_link }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ ucfirst($interview->status) }}</span>
                            </td>
                            <td>{{ $interview->remarks ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('agent.interviews.show', $interview->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('agent.interviews.edit', $interview->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('agent.interviews.destroy', $interview->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete interview?')" type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center">No accepted interviews found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Postponed Tab Pane -->
        <div class="tab-pane fade {{ $activeTab == 'postponed' ? 'show active' : '' }}" id="postponed" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="postponedTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Job Title</th>
                            <th>Interview Start</th>
                            <th>Interview End</th>
                            <th>Interview Link</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($postponedInterviews as $interview)
                        <tr>
                            <td>{{ $interview->id }}</td>
                            <td>{{ $interview->application->job->job_title }}</td>
                            <td>{{ \Carbon\Carbon::parse($interview->interview_start)->format('Y-m-d H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($interview->interview_end)->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ $interview->interview_link }}" target="_blank">
                                    {{ $interview->interview_link }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($interview->status) }}</span>
                            </td>
                            <td>{{ $interview->remarks ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('agent.interviews.show', $interview->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('agent.interviews.edit', $interview->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('agent.interviews.destroy', $interview->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete interview?')" type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center">No postponed interviews found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rejected Tab Pane -->
        <div class="tab-pane fade {{ $activeTab == 'rejected' ? 'show active' : '' }}" id="rejected" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="rejectedTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Job Title</th>
                            <th>Interview Start</th>
                            <th>Interview End</th>
                            <th>Interview Link</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rejectedInterviews as $interview)
                        <tr>
                            <td>{{ $interview->id }}</td>
                            <td>{{ $interview->application->job->job_title }}</td>
                            <td>{{ \Carbon\Carbon::parse($interview->interview_start)->format('Y-m-d H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($interview->interview_end)->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ $interview->interview_link }}" target="_blank">
                                    {{ $interview->interview_link }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-danger">{{ ucfirst($interview->status) }}</span>
                            </td>
                            <td>{{ $interview->remarks ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('agent.interviews.show', $interview->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('agent.interviews.edit', $interview->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('agent.interviews.destroy', $interview->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete interview?')" type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center">No rejected interviews found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
