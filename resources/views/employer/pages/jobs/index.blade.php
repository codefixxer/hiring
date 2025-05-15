@extends('employer.layouts.app')

@section('content')
    <h6 class="mb-0 text-uppercase">Job Listings</h6>
    <hr>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="jobsTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Job Title</th>
                            <th>Posting Date</th>
                            <th>Closing Date</th>
                            <th>Assigned Agents</th>
                            <th>Status</th>
                            <th>Salary Range</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $job->job_title }}</td>
                                <td>{{ $job->posting_date->format('Y-m-d') }}</td>
                                <td>{{ $job->closing_date->format('Y-m-d') }}</td>

                                <td>
                                    @php
                                        $agents = $job->agent_ids
                                            ? $job->agents()
                                            : [];
                                    @endphp

                                    @if(count($agents))
                                        {{ $agents->pluck('name')->implode(', ') }}
                                    @else
                                        <span class="text-muted">Not Assigned</span>
                                    @endif
                                </td>

                                <td>{{ ucfirst($job->status) }}</td>
                                <td>
                                    ${{ number_format($job->min_salary, 2) }} –
                                    ${{ number_format($job->max_salary, 2) }}
                                </td>
                                <td>
                                    <a href="{{ route('employer.jobs.show',  $job->id) }}"
                                       class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('employer.jobs.edit',  $job->id) }}"
                                       class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('employer.jobs.destroy', $job->id) }}"
                                          method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
