@extends('user.layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Apply for Job: {{ $job->job_title }}</h5>
                </div>
                <div class="card-body p-4">
                    <form class="row g-3 needs-validation" action="{{ route('user.applications.store') }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf

                        <!-- Hidden job_id -->
                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                        <!-- CV File -->
                        <div class="col-md-6">
                            <label for="cvFile" class="form-label">Upload CV</label>
                            <input type="file" class="form-control" id="cvFile" name="cv_file">
                            <small class="form-text text-muted">pdf, doc, docx</small>
                        </div>

                        <!-- CV Link -->
                        <div class="col-md-6">
                            <label for="cvLink" class="form-label">CV Link</label>
                            <input type="url" class="form-control" id="cvLink" name="cv_link" placeholder="https://">
                        </div>

                        <!-- Cover Letter -->
                        <div class="col-md-12">
                            <label for="coverLetter" class="form-label">Cover Letter</label>
                            <textarea class="form-control" id="coverLetter" name="cover_letter" rows="4" placeholder="Write your cover letter"
                                required></textarea>
                            <div class="invalid-feedback">Please provide a cover letter.</div>
                        </div>

                        <!-- Skills -->
                        @isset($skills)
                            <div class="col-md-12">
                                <label class="form-label">Your Skills</label>
                                <div class="row">
                                    @foreach ($skills as $skill)
                                        @php
                                            $slug = \Illuminate\Support\Str::slug($skill);
                                        @endphp
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="selected_skills[]"
                                                    value="{{ $skill }}" id="skill_{{ $slug }}">
                                                <label class="form-check-label" for="skill_{{ $slug }}">
                                                    {{ ucfirst($skill) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endisset

                        <!-- Expected Salary -->
                        <div class="col-md-6">
                            <label for="expectedSalary" class="form-label">Expected Salary</label>
                            <input type="number" class="form-control" id="expectedSalary" name="expected_salary"
                                min="0" placeholder="e.g., 50000">
                        </div>

                        <!-- Available Start Date -->
                        <div class="col-md-6">
                            <label for="availableStartDate" class="form-label">Available Start Date</label>
                            <input type="date" class="form-control" id="availableStartDate" name="available_start_date">
                        </div>

                        <!-- Submit -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-grd-primary px-4">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
