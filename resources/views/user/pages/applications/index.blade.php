@extends('user.layouts.app')

@section('content')
<h6 class="mb-0 text-uppercase">My Applications</h6>
<hr>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="applicationsTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Job Title</th>
                        <th>CV</th>
                        <th>Status</th>
                        <th>Applied Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $application->job->job_title }}</td>
                        <td>
                            @if($application->cv_file)
                                <a href="{{ asset('storage/'.$application->cv_file) }}" target="_blank">View File</a>
                            @elseif($application->cv_link)
                                <a href="{{ $application->cv_link }}" target="_blank">View CV Link</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ ucfirst($application->status) }}</td>
                        <td>{{ $application->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('user.applications.show', $application->id) }}" class="btn btn-sm btn-info">View</a>
                             
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
