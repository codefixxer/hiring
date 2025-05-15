<?php

namespace App\Http\Controllers\User;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserApplicationController extends Controller
{
    /**
     * Show list of applications for the authenticated user.
     */
    public function index()
    {
        $applications = Application::with('job')
            ->where('employee_id', Auth::id())
            ->latest()
            ->get();

        return view('user.pages.applications.index', compact('applications'));
    }

    /**
     * Show a specific application (only if belongs to the user).
     */
    public function show($id)
    {
        $application = Application::with('job')
            ->where('id', $id)
            ->where('employee_id', Auth::id())
            ->firstOrFail();

        return view('user.pages.applications.show', compact('application'));
    }

    /**
     * Show application form for a specific job.
     */
    // app/Http/Controllers/User/UserApplicationController.php

public function create(Request $request)
{
    $job = Job::findOrFail($request->job_id);

    // Convert comma-separated skills string into an array
    $skills = $job->skills
        ? array_map('trim', explode(',', $job->skills))
        : [];

    return view('user.pages.applications.create', compact('job', 'skills'));
}



    /**
     * Store a newly submitted application.
     */
   public function store(Request $request)
{
    $validatedData = $request->validate([
        'job_id'               => 'required|exists:jobs,id',
        'cv_file'              => 'nullable|file|mimes:pdf,doc,docx',
        'cv_link'              => 'nullable|url',
        'cover_letter'         => 'required|string',
        'selected_skills'      => 'nullable|array',
        'selected_skills.*'    => 'string',
        'expected_salary'      => 'nullable|numeric|min:0',
        'available_start_date' => 'nullable|date',
    ]);

    $validatedData['employee_id'] = Auth::id();
    $validatedData['status'] = 'applied';

    // Convert selected_skills array to comma-separated string
    $validatedData['selected_skills'] = $request->has('selected_skills')
        ? implode(',', $request->selected_skills)
        : null;

    // Handle CV upload
    if ($request->hasFile('cv_file')) {
        $validatedData['cv_file'] = $request->file('cv_file')->store('cvs', 'public');
    }

    Application::create($validatedData);

    return redirect()->route('user.applications.index')
                     ->with('success', 'Application submitted successfully.');
}

}
