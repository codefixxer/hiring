<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Display a list of the authenticated employee's employer.pages.applications.
     */
    public function index()
    {
        $applications = Application::where('employee_id', Auth::id())
                                   ->with('job')
                                   ->get();
        return view('employer.pages.applications.index', compact('applications'));
    }
    
    /**
     * Show the form for creating a new application.
     * Optionally, a job id can be passed (?job_id=123) so the form loads required skills.
     */
    public function create(Request $request)
    {
        $job = null;
        if ($request->has('job_id')) {
            $job = Job::with('skills')->findOrFail($request->job_id);
        }
        return view('employer.pages.applications.create', compact('job'));
    }
    
    /**
     * Store a new application in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'job_id'               => 'required|exists:jobs,id',
            'cv_file'              => 'nullable|file|mimes:pdf,doc,docx',
            'cv_link'              => 'nullable|url',
            'cover_letter'         => 'required|string',
            // Expect an array from the checkboxes rather than a string
            'selected_skills'      => 'nullable|array',
            'selected_skills.*'    => 'string',
            'expected_salary'      => 'nullable|numeric|min:0',
            'available_start_date' => 'nullable|date',
            'status'               => 'nullable|in:applied,reviewed,accepted,rejected',
        ]);
    
        $validatedData['employee_id'] = Auth::id();
    
        // Handle CV file upload if provided.
        if ($request->hasFile('cv_file')) {
            $filePath = $request->file('cv_file')->store('cvs', 'public');
            $validatedData['cv_file'] = $filePath;
        }
        
        // Set default status if not provided.
        if (!isset($validatedData['status'])) {
            $validatedData['status'] = 'applied';
        }
        
        Application::create($validatedData);
        
        return redirect()->route('employer.applications.index')
                         ->with('success', 'Application submitted successfully.');
    }
    
    
    /**
     * Display the specified application.
     */
    public function show($id)
    {
        $application = Application::with('job')->findOrFail($id);
        return view('employer.pages.applications.show', compact('application'));
    }
    
    /**
     * Show the form for editing an existing application.
     */
    public function edit($id)
    {
        $application = Application::findOrFail($id);
        // Load the associated job (with skills) so we can display the checkboxes.
        $job = Job::with('skills')->findOrFail($application->job_id);
        return view('employer.pages.applications.create', compact('application', 'job'));
    }
    
    /**
     * Update the specified application in storage.
     */
    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        
        $validatedData = $request->validate([
            'job_id'               => 'required|exists:jobs,id',
            'cv_file'              => 'nullable|file|mimes:pdf,doc,docx',
            'cv_link'              => 'nullable|url',
            'cover_letter'         => 'required|string',
            'selected_skills'      => 'nullable|array',
            'selected_skills.*'    => 'string',
            'expected_salary'      => 'nullable|numeric|min:0',
            'available_start_date' => 'nullable|date',
            'remarks'              => 'nullable|string',

            'status'               => 'nullable|in:applied,reviewed,accepted,rejected',
        ]);
    
        if ($request->hasFile('cv_file')) {
            $filePath = $request->file('cv_file')->store('cvs', 'public');
            $validatedData['cv_file'] = $filePath;
        } else {
            // Do not change the cv_file if no new file was uploaded.
            unset($validatedData['cv_file']);
        }
        
        $application->update($validatedData);
        
        return redirect()->route('employer.applications.index')
                         ->with('success', 'Application updated successfully.');
    }
    
    
    /**
     * Remove the specified application from storage.
     */
    public function destroy($id)
    {
        $application = Application::findOrFail($id);
        $application->delete();
        
        return redirect()->route('employer.pages.applications.index')
                         ->with('success', 'Application deleted successfully.');
    }



    public function approve(Request $request, $id)
    {
        // Find the application; if not found, abort with 404.
        $application = Application::findOrFail($id);

        // Update the status to 'approved'
        $application->update([
            'status' => 'accepted'
        ]);

        return redirect()->route('employer.applications.show', $id)
                         ->with('success', 'Application approved successfully.');
    }

    /**
     * Reject an application.
     * Expects a 'remarks' input in the request.
     */
    public function reject(Request $request, $id)
    {
        // Validate the remarks field.
        $request->validate([
            'remarks' => 'required|string|max:255'
        ]);

        $application = Application::findOrFail($id);

        // Update the application with status 'rejected' and store remarks.
        $application->update([
            'status'  => 'rejected',
            'remarks' => $request->input('remarks')
        ]);

        return redirect()->route('employer.applications.show', $id)
                         ->with('success', 'Application rejected with remarks.');
    }
}
