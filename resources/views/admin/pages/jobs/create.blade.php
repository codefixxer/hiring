@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-xl-8 mx-auto">
        <div class="card">
            <div class="card-header px-4 py-3">
                <!-- Change the heading if editing -->
                <h5 class="mb-0">{{ isset($job) ? 'Edit Advanced Job' : 'Create Advanced Job' }}</h5>
            </div>
            <div class="card-body p-4">
                <!-- Form action and method adjustment based on create or edit -->
                <form class="row g-3 needs-validation" 
                      action="{{ isset($job) ? route('admin.jobs.update', $job->id) : route('admin.jobs.store') }}" 
                      method="POST" novalidate>
                    @csrf
                    @if(isset($job))
                        @method('PUT')
                    @endif

                    <!-- Job Details Section -->
                    <div class="col-md-12">
                        <label for="jobTitle" class="form-label">Job Title</label>
                        <input type="text" class="form-control" id="jobTitle" 
                               name="job_title" placeholder="Enter Job Title" 
                               value="{{ old('job_title', isset($job) ? $job->job_title : '') }}" 
                               required>
                        <div class="invalid-feedback">
                            Please provide a job title.
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="jobDescription" class="form-label">Job Description</label>
                        <textarea class="form-control" id="jobDescription" 
                                  name="job_description" rows="4" placeholder="Enter Job Description" 
                                  required>{{ old('job_description', isset($job) ? $job->job_description : '') }}</textarea>
                        <div class="invalid-feedback">
                            Please provide a job description.
                        </div>
                    </div>

                    <!-- Skills (Tag-style Input) -->
                    <div class="col-md-12">
                        <label for="skillsInput" class="form-label">Required Skills</label>
                        <input type="text" class="form-control" id="skillsInput" 
                               placeholder="Type a skill and press Enter">
                        <small class="form-text text-muted">
                            Type a skill and press Enter to add. No limit on tags.
                        </small>
                        <!-- Container where the skill tags will appear -->
                        <div class="mt-2" id="skillsContainer"></div>
                        <!-- Hidden input to store the final comma-separated skills list -->
                        <input type="hidden" name="required_skills" id="skillsHidden" 
                               value="{{ old('required_skills', isset($job) ? implode(',', $job->skills->pluck('skill_name')->toArray()) : '') }}">
                    </div>
                    
                    <!-- Additional Criteria Section -->
                    <div class="col-md-6">
                        <label for="preferredGender" class="form-label">Preferred Gender</label>
                        <select class="form-select" id="preferredGender" name="preferred_gender">
                            <option value="any" {{ old('preferred_gender', isset($job) ? $job->preferred_gender : 'any') == 'any' ? 'selected' : '' }}>Any</option>
                            <option value="male" {{ old('preferred_gender', isset($job) ? $job->preferred_gender : '') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('preferred_gender', isset($job) ? $job->preferred_gender : '') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('preferred_gender', isset($job) ? $job->preferred_gender : '') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="experience" class="form-label">Minimum Experience (Years)</label>
                        <input type="number" class="form-control" id="experience" 
                               name="minimum_experience" placeholder="e.g., 2" min="0" 
                               value="{{ old('minimum_experience', isset($job) ? $job->minimum_experience : '') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="educationLevel" class="form-label">Education Level</label>
                        <select class="form-select" id="educationLevel" name="education_level" required>
                            <option value="" disabled {{ old('education_level', isset($job) ? $job->education_level : '') == '' ? 'selected' : '' }}>Choose Education Level...</option>
                            <option value="high_school" {{ old('education_level', isset($job) ? $job->education_level : '') == 'high_school' ? 'selected' : '' }}>High School</option>
                            <option value="bachelor" {{ old('education_level', isset($job) ? $job->education_level : '') == 'bachelor' ? 'selected' : '' }}>Bachelor's Degree</option>
                            <option value="master" {{ old('education_level', isset($job) ? $job->education_level : '') == 'master' ? 'selected' : '' }}>Master's Degree</option>
                            <option value="phd" {{ old('education_level', isset($job) ? $job->education_level : '') == 'phd' ? 'selected' : '' }}>PhD</option>
                            <option value="other" {{ old('education_level', isset($job) ? $job->education_level : '') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select an education level.
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="languages" class="form-label">Languages Required</label>
                        <input type="text" class="form-control" id="languages" name="languages" 
                               placeholder="e.g., English, Spanish" 
                               value="{{ old('languages', isset($job) ? $job->languages : '') }}">
                        <div class="form-text">
                            Separate languages with commas.
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="certifications" class="form-label">Certifications (Optional)</label>
                        <input type="text" class="form-control" id="certifications" name="certifications" 
                               placeholder="List certifications if needed" 
                               value="{{ old('certifications', isset($job) ? $job->certifications : '') }}">
                        <div class="form-text">
                            Separate certifications with commas.
                        </div>
                    </div>
                    
                    <!-- Salary Range and Working Hours Section -->
                    <div class="col-md-6">
                        <label for="minSalary" class="form-label">Minimum Salary</label>
                        <input type="number" class="form-control" id="minSalary" name="min_salary" 
                               placeholder="Enter minimum salary" min="0" required 
                               value="{{ old('min_salary', isset($job) ? $job->min_salary : '') }}">
                        <div class="invalid-feedback">
                            Please provide the minimum salary.
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="maxSalary" class="form-label">Maximum Salary</label>
                        <input type="number" class="form-control" id="maxSalary" name="max_salary" 
                               placeholder="Enter maximum salary" min="0" required 
                               value="{{ old('max_salary', isset($job) ? $job->max_salary : '') }}">
                        <div class="invalid-feedback">
                            Please provide the maximum salary.
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="workingHours" class="form-label">Working Hours per Week</label>
                        <input type="number" class="form-control" id="workingHours" name="working_hours" 
                               placeholder="e.g., 40" min="0" required 
                               value="{{ old('working_hours', isset($job) ? $job->working_hours : '') }}">
                        <div class="invalid-feedback">
                            Please provide the working hours per week.
                        </div>
                    </div>
                    
                    <!-- Posting & Closing Dates -->
                    <div class="col-md-6">
                        <label for="postingDate" class="form-label">Posting Date</label>
                        <input type="date" class="form-control" id="postingDate" name="posting_date" required 
                               value="{{ old('posting_date', isset($job) ? $job->posting_date : '') }}">
                        <div class="invalid-feedback">
                            Please provide the posting date.
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="closingDate" class="form-label">Closing Date</label>
                        <input type="date" class="form-control" id="closingDate" name="closing_date" required 
                               value="{{ old('closing_date', isset($job) ? $job->closing_date : '') }}">
                        <div class="invalid-feedback">
                            Please provide the closing date.
                        </div>
                    </div>
                    
                    <!-- Job Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label">Job Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="" disabled {{ old('status', isset($job) ? $job->status : '') == '' ? 'selected' : '' }}>Choose...</option>
                            <option value="active" {{ old('status', isset($job) ? $job->status : '') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ old('status', isset($job) ? $job->status : '') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="closed" {{ old('status', isset($job) ? $job->status : '') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a status.
                        </div>
                    </div>
                    
                    <!-- Submit & Reset Buttons -->
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-grd-primary px-4">Submit</button>
                            <button type="reset" class="btn btn-grd-info px-4">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tag Input Script for Skills -->
<script>
    // Initialize the skills array.
    // For edit mode, pre-populate skills from the job's skills (or hidden input), otherwise empty.
    let skills = {!! json_encode(isset($job) ? $job->skills->pluck('skill_name')->toArray() : []) !!};

    const skillsInput = document.getElementById('skillsInput');
    const skillsContainer = document.getElementById('skillsContainer');
    const skillsHidden = document.getElementById('skillsHidden');

    // Function to render the skill badges in the skillsContainer.
    function renderSkills() {
        skillsContainer.innerHTML = '';
        skills.forEach((skill, index) => {
            const badge = document.createElement('span');
            badge.classList.add('badge', 'bg-primary', 'me-2');
            badge.style.marginBottom = '5px';
            badge.textContent = skill;

            const closeBtn = document.createElement('span');
            closeBtn.textContent = ' ×';
            closeBtn.style.cursor = 'pointer';
            closeBtn.classList.add('ms-1');
            closeBtn.addEventListener('click', () => removeSkill(index));
            
            badge.appendChild(closeBtn);
            skillsContainer.appendChild(badge);
        });
        // Update the hidden input with a comma-separated skills list.
        skillsHidden.value = skills.join(',');
    }

    // Function to remove a skill.
    function removeSkill(index) {
        skills.splice(index, 1);
        renderSkills();
    }

    // Add a new skill when the user presses Enter.
    skillsInput.addEventListener('keydown', (e) => {
        if(e.key === 'Enter'){
            e.preventDefault();
            const inputVal = skillsInput.value.trim();
            // Basic validation for letters, numbers, and spaces.
            const validPattern = /^[a-zA-Z0-9]+(\s[a-zA-Z0-9]+)*$/;
            if(inputVal && !skills.includes(inputVal)){
                if(validPattern.test(inputVal)){
                    skills.push(inputVal);
                    renderSkills();
                } else {
                    alert('Please use letters and numbers only (spaces allowed).');
                }
            }
            skillsInput.value = '';
        }
    });

    // Render skills on page load.
    renderSkills();
</script>
@endsection
