@extends('agent.layouts.app')

@section('content')
<h6 class="mb-0 text-uppercase">Assigned Applications</h6>
<hr>
<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="applicationsTable" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>#</th>
            <th>Job Title</th>
            <th>Applicant</th>
            <th>CV</th>
            <th>Status</th>
            <th>Applied On</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($applications as $app)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $app->job->job_title }}</td>
            <td>{{ $app->applicant->name }}</td>
            <td>
              @if($app->cv_file)
                <a href="{{ asset('storage/'.$app->cv_file) }}" target="_blank">View File</a>
              @elseif($app->cv_link)
                <a href="{{ $app->cv_link }}" target="_blank">View Link</a>
              @else
                N/A
              @endif
            </td>
            <td>{{ ucfirst($app->status) }}</td>
            <td>{{ $app->created_at->format('Y-m-d') }}</td>
            <td>
              <a href="{{ route('agent.applications.show', $app->id) }}"
                 class="btn btn-sm btn-info">View</a>

               

              
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
