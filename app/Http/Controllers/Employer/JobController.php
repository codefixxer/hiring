<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::where('employer_id', Auth::id())->get();
        return view('employer.pages.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $agents = User::where('role', 'agent')->get();
        return view('employer.pages.jobs.create', compact('agents'));
    }

    public function edit(string $id)
    {
        $job = Job::findOrFail($id);
        $agents = User::where('role', 'agent')->get();
        return view('employer.pages.jobs.create', compact('job', 'agents'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'job_title'           => 'required|string|max:255',
        'job_description'     => 'required|string',
        'preferred_gender'    => 'required|in:any,male,female,other',
        'minimum_experience'  => 'nullable|integer|min:0',
        'education_level'     => 'nullable|string',
        'languages'           => 'nullable|string',
        'certifications'      => 'nullable|string',
        'min_salary'          => 'required|numeric|min:0',
        'max_salary'          => 'required|numeric|min:0',
        'working_hours'       => 'required|integer|min:0',
        'posting_date'        => 'required|date',
        'closing_date'        => 'required|date',
        'status'              => 'required|in:active,draft,closed',
        'required_skills'     => 'nullable|string',
        'assigned_agents'     => 'nullable',
        'assigned_agents.*'   => 'exists:users,id',
    ]);

    $validated['employer_id'] = Auth::id();

    if ($request->filled('required_skills')) {
        $skills = array_filter(array_map('trim', explode(',', $request->required_skills)));
        $validated['skills'] = implode(',', $skills);
    }

    $agentInput = $request->input('assigned_agents', []);
    $validated['agent_ids'] = array_map('intval', is_array($agentInput) ? $agentInput : [$agentInput]);
    unset($validated['assigned_agents']); // 🔥 this prevents storing invalid field

    Job::create($validated);

    return redirect()->route('employer.jobs.index')
                     ->with('success', 'Job created successfully.');
}


    public function show($id)
    {
        $job = Job::with('employer')->findOrFail($id);
        return view('employer.pages.jobs.show', compact('job'));
    }

 public function update(Request $request, string $id)
{
    $job = Job::findOrFail($id);

    // 1️⃣ Validate everything *except* the multi‐select
    $validated = $request->validate([
        'job_title'           => 'required|string|max:255',
        'job_description'     => 'required|string',
        'preferred_gender'    => 'required|in:any,male,female,other',
        'minimum_experience'  => 'nullable|integer|min:0',
        'education_level'     => 'nullable|string',
        'languages'           => 'nullable|string',
        'certifications'      => 'nullable|string',
        'min_salary'          => 'required|numeric|min:0',
        'max_salary'          => 'required|numeric|min:0',
        'working_hours'       => 'required|integer|min:0',
        'posting_date'        => 'required|date',
        'closing_date'        => 'required|date',
        'status'              => 'required|in:active,draft,closed',
        'required_skills'     => 'nullable|string',
        // ← no assigned_agents validation here
    ]);

    // 2️⃣ Turn your comma-string into the `skills` column
    if ($request->filled('required_skills')) {
        $skills = array_filter(array_map('trim', explode(',', $request->required_skills)));
        $validated['skills'] = implode(',', $skills);
    } else {
        $validated['skills'] = null;
    }

    // 3️⃣ Always coerce whatever came back into an integer array
    $validated['agent_ids'] = array_map(
        'intval',
        (array) $request->input('assigned_agents', [])
    );

    // 4️⃣ Persist
    $job->update($validated);

    return redirect()
        ->route('employer.jobs.index')
        ->with('success', 'Job updated successfully.');
}



    public function destroy(string $id)
    {
        Job::findOrFail($id)->delete();

        return redirect()->route('employer.jobs.index')
                         ->with('success', 'Job deleted successfully.');
    }
}
