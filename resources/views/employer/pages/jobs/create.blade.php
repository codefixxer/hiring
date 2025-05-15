@extends('employer.layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">{{ isset($job) ? 'Edit Advanced Job' : 'Create Advanced Job' }}</h5>
                </div>
                <div class="card-body p-4">
                    <form class="row g-3 needs-validation"
                          action="{{ isset($job) ? route('employer.jobs.update', $job->id) : route('employer.jobs.store') }}"
                          method="POST" novalidate>
                        @csrf
                        @if(isset($job))
                            @method('PUT')
                        @endif

                        <div class="col-md-12">
                            <label for="jobTitle" class="form-label">Job Title</label>
                            <input type="text" class="form-control" id="jobTitle" name="job_title"
                                   placeholder="Enter Job Title"
                                   value="{{ old('job_title', $job->job_title ?? '') }}" required>
                            <div class="invalid-feedback">Please provide a job title.</div>
                        </div>

                        <div class="col-md-12">
                            <label for="jobDescription" class="form-label">Job Description</label>
                            <textarea class="form-control" id="jobDescription" name="job_description" rows="4"
                                      placeholder="Enter Job Description" required>{{ old('job_description', $job->job_description ?? '') }}</textarea>
                            <div class="invalid-feedback">Please provide a job description.</div>
                        </div>

                        <!-- Skills (Tag-style Input) -->
                        <div class="col-md-12">
                            <label for="skillsInput" class="form-label">Required Skills</label>
                            <input type="text" class="form-control" id="skillsInput"
                                   placeholder="Type a skill and press Enter">
                            <small class="form-text text-muted">Type a skill and press Enter to add. No limit on tags.</small>
                            <div class="mt-2" id="skillsContainer"></div>
                            <input type="hidden" name="required_skills" id="skillsHidden"
                                   value="{{ old('required_skills', $job->skills ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="preferredGender" class="form-label">Preferred Gender</label>
                            <select class="form-select" id="preferredGender" name="preferred_gender">
                                @foreach(['any','male','female','other'] as $gender)
                                    <option value="{{ $gender }}"
                                        {{ old('preferred_gender', $job->preferred_gender ?? 'any') === $gender ? 'selected' : '' }}>
                                        {{ ucfirst($gender) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="experience" class="form-label">Minimum Experience (Years)</label>
                            <input type="number" class="form-control" id="experience" name="minimum_experience"
                                   placeholder="e.g., 2" min="0"
                                   value="{{ old('minimum_experience', $job->minimum_experience ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="educationLevel" class="form-label">Education Level</label>
                            <select class="form-select" id="educationLevel" name="education_level" required>
                                <option value="" disabled {{ empty(old('education_level', $job->education_level ?? '')) ? 'selected' : '' }}>Choose...</option>
                                @foreach(['high_school'=>'High School','bachelor'=>"Bachelor's Degree",'master'=>"Master's Degree",'phd'=>'PhD','other'=>'Other'] as $val=>$label)
                                    <option value="{{ $val }}"
                                        {{ old('education_level', $job->education_level ?? '') === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select an education level.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="languages" class="form-label">Languages Required</label>
                            <input type="text" class="form-control" id="languages" name="languages"
                                   placeholder="e.g., English, Spanish"
                                   value="{{ old('languages', $job->languages ?? '') }}">
                            <div class="form-text">Separate languages with commas.</div>
                        </div>

                        <div class="col-md-12">
                            <label for="certifications" class="form-label">Certifications (Optional)</label>
                            <input type="text" class="form-control" id="certifications" name="certifications"
                                   placeholder="List certifications if needed"
                                   value="{{ old('certifications', $job->certifications ?? '') }}">
                            <div class="form-text">Separate certifications with commas.</div>
                        </div>

                        <!-- Salary & Hours -->
                        <div class="col-md-6">
                            <label for="minSalary" class="form-label">Minimum Salary</label>
                            <input type="number" class="form-control" id="minSalary" name="min_salary"
                                   placeholder="Enter minimum salary" min="0" required
                                   value="{{ old('min_salary', $job->min_salary ?? '') }}">
                            <div class="invalid-feedback">Please provide the minimum salary.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="maxSalary" class="form-label">Maximum Salary</label>
                            <input type="number" class="form-control" id="maxSalary" name="max_salary"
                                   placeholder="Enter maximum salary" min="0" required
                                   value="{{ old('max_salary', $job->max_salary ?? '') }}">
                            <div class="invalid-feedback">Please provide the maximum salary.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="workingHours" class="form-label">Working Hours per Week</label>
                            <input type="number" class="form-control" id="workingHours" name="working_hours"
                                   placeholder="e.g., 40" min="0" required
                                   value="{{ old('working_hours', $job->working_hours ?? '') }}">
                            <div class="invalid-feedback">Please provide the working hours per week.</div>
                        </div>

                        <!-- Dates -->
                        <div class="col-md-6">
                            <label for="postingDate" class="form-label">Posting Date</label>
                            <input type="date" class="form-control" id="postingDate" name="posting_date" required
                                   value="{{ old('posting_date', $job->posting_date ?? '') }}">
                            <div class="invalid-feedback">Please provide the posting date.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="closingDate" class="form-label">Closing Date</label>
                            <input type="date" class="form-control" id="closingDate" name="closing_date" required
                                   value="{{ old('closing_date', $job->closing_date ?? '') }}">
                            <div class="invalid-feedback">Please provide the closing date.</div>
                        </div>

                        <!-- Agents Multi-select -->
                        <div class="col-md-12">
                            <label class="form-label">Assign Agents</label>
                            <select name="assigned_agents[]" multiple class="selectpicker form-control" data-live-search="true">
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}"
                                        {{ in_array($agent->id, old('assigned_agents', $job->agent_ids ?? [])) ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Job Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="" disabled {{ empty(old('status', $job->status ?? '')) ? 'selected' : '' }}>Choose...</option>
                                @foreach(['active','draft','closed'] as $st)
                                    <option value="{{ $st }}"
                                        {{ old('status', $job->status ?? '') === $st ? 'selected' : '' }}>
                                        {{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select a status.</div>
                        </div>

                        <!-- Buttons -->
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
        // Initialize the skills array from the comma-string
        let skills = {!! json_encode((isset($job) && $job->skills) ? array_map('trim', explode(',', $job->skills)) : []) !!};

        const skillsInput = document.getElementById('skillsInput');
        const skillsContainer = document.getElementById('skillsContainer');
        const skillsHidden = document.getElementById('skillsHidden');

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
            skillsHidden.value = skills.join(',');
        }

        function removeSkill(index) {
            skills.splice(index, 1);
            renderSkills();
        }

        skillsInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const val = skillsInput.value.trim();
                const validPattern = /^[a-zA-Z0-9]+(\s[a-zA-Z0-9]+)*$/;
                if (val && !skills.includes(val) && validPattern.test(val)) {
                    skills.push(val);
                    renderSkills();
                } else if (val && !validPattern.test(val)) {
                    alert('Please use letters and numbers only.');
                }
                skillsInput.value = '';
            }
        });

        renderSkills();
    </script>
@endsection
