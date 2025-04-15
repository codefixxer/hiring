@extends('admin.layouts.app')

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
                        <th>Status</th>
                        <th>Salary Range</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $job->job_title }}</td>
                        <td>{{ \Carbon\Carbon::parse($job->posting_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($job->closing_date)->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($job->status) }}</td>
                        <td>${{ number_format($job->min_salary,2) }} - ${{ number_format($job->max_salary,2) }}</td>
                        <td>
                            <a href="{{ route('admin.jobs.show', $job->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this job?')">
                                    Delete
                                </button>
                            </form>

                            <a href="{{ route('admin.applications.create', ['job_id' => $job->id]) }}" class="btn btn-sm btn-success">Apply</a>

                        </td>
                        
                    </tr>
                    @endforeach
                </tbody>
         
            </table>
        </div>
    </div>
</div>
@endsection
