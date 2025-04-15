@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-xl-8 mx-auto">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">{{ isset($application) ? 'Edit Application' : 'Apply for Job' }}</h5>
            </div>
            <div class="card-body p-4">
                <form class="row g-3 needs-validation" 
                      action="{{ isset($application) ? route('admin.applications.update', $application->id) : route('admin.applications.store') }}" 
                      method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @if(isset($application))
                        @method('PUT')
                    @endif

                    <!-- Hidden input: job_id (passed either from $job or from $application) -->
                    <input type="hidden" name="job_id" value="{{ old('job_id', isset($job) ? $job->id : (isset($application) ? $application->job_id : '')) }}">
                    
                    <!-- CV File & CV Link -->
                    <div class="col-md-6">
                        <label for="cvFile" class="form-label">Upload CV (File)</label>
                        <input type="file" class="form-control" id="cvFile" name="cv_file">
                        <small class="form-text">Allowed file types: pdf, doc, docx</small>
                    </div>
                    <div class="col-md-6">
                        <label for="cvLink" class="form-label">CV Link</label>
                        <input type="url" class="form-control" id="cvLink" name="cv_link" placeholder="Enter CV link"
                               value="{{ old('cv_link', isset($application) ? $application->cv_link : '') }}">
                    </div>
                    
                    <!-- Cover Letter -->
                    <div class="col-md-12">
                        <label for="coverLetter" class="form-label">Cover Letter</label>
                        <textarea class="form-control" id="coverLetter" name="cover_letter" rows="4" placeholder="Write your cover letter" required>{{ old('cover_letter', isset($application) ? $application->cover_letter : '') }}</textarea>
                        <div class="invalid-feedback">
                            Please provide a cover letter.
                        </div>
                    </div>
                    
                    <!-- Selected Skills (Checkboxes) -->
                    @if(isset($job))
                    <div class="col-md-12">
                        <label class="form-label">Select the Skills you have</label>
                        <div class="row">
                            @foreach($job->skills as $skill)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <!-- Note: use 'selected_skills[]' and set value to $skill->id -->
                                        <input class="form-check-input" type="checkbox" name="selected_skills[]" value="{{ $skill->id }}"
                                               id="skill_{{ $skill->id }}"
                                               @if(isset($application) && is_array($application->selected_skills) && in_array($skill->id, $application->selected_skills)) checked @endif>
                                        <label class="form-check-label" for="skill_{{ $skill->id }}">
                                            {{ $skill->skill_name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Advanced Inputs: Expected Salary and Available Start Date -->
                    <div class="col-md-6">
                        <label for="expectedSalary" class="form-label">Expected Salary</label>
                        <input type="number" class="form-control" id="expectedSalary" name="expected_salary" placeholder="Enter your expected salary" min="0"
                               value="{{ old('expected_salary', isset($application) ? $application->expected_salary : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="availableStartDate" class="form-label">Available Start Date</label>
                        <input type="date" class="form-control" id="availableStartDate" name="available_start_date"
                               value="{{ old('available_start_date', isset($application) ? $application->available_start_date : '') }}">
                    </div>
                    
                    <!-- Submit & Reset Buttons -->
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-grd-primary px-4">{{ isset($application) ? 'Update' : 'Submit' }}</button>
                            <button type="reset" class="btn btn-grd-info px-4">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
