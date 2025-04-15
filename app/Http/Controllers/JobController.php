<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobSkill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // Retrieves jobs for the authenticated employer (adjust as needed)
        $jobs = Job::where('employer_id', Auth::id())->get();
        return view('admin.pages.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.jobs.create');
    }

    public function edit(string $id)
    {
        $job = Job::with('skills')->findOrFail($id);

        return view('admin.pages.jobs.create', compact('job'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
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
            'required_skills'     => 'nullable|string',  // comma-separated skills from hidden input
        ]);

        // Set employer_id from the authenticated user.
        $validatedData['employer_id'] = Auth::id();

        // Create the job posting.
        $job = Job::create($validatedData);

        // Process and store job skills if provided.
        if ($request->filled('required_skills')) {
            $skills = explode(',', $request->required_skills);
            foreach ($skills as $skill) {
                $trimSkill = trim($skill);
                if ($trimSkill !== '') {
                    JobSkill::create([
                        'job_id'    => $job->id,
                        'skill_name'=> $trimSkill,
                    ]);
                }
            }
        }

        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Job created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $job = Job::with('skills')->findOrFail($id);
        return view('admin.pages.jobs.show', compact('job'));
    }


    /**
     * Show the form for editing the specified resource.
     */
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $job = Job::findOrFail($id);
    
        $validatedData = $request->validate([
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
        ]);
    
        // Update job fields
        $job->update($validatedData);
    
        // Delete old skills
        JobSkill::where('job_id', $job->id)->delete();
    
        // Add new skills
        if ($request->filled('required_skills')) {
            $skills = explode(',', $request->required_skills);
            foreach ($skills as $skill) {
                $trimSkill = trim($skill);
                if ($trimSkill !== '') {
                    JobSkill::create([
                        'job_id'    => $job->id,
                        'skill_name'=> $trimSkill,
                    ]);
                }
            }
        }
    
        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Job updated successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $job = Job::findOrFail($id);

    // Automatically deletes associated skills if set to cascade in DB
    $job->delete();

    return redirect()->route('admin.jobs.index')
                     ->with('success', 'Job deleted successfully.');
}

}
