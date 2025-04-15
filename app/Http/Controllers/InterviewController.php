<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use Illuminate\Http\Request;

class InterviewController extends Controller
{




    

    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'pending'); // default tab is "pending"
    
        $pendingInterviews = Interview::where('status', 'pending')
            ->with('application.job')
            ->get();

        $acceptedInterviews = Interview::where('status', 'accepted')
            ->with('application.job')
            ->get();

        $postponedInterviews = Interview::where('status', 'postponed')
            ->with('application.job')
            ->get();

        $rejectedInterviews = Interview::where('status', 'rejected')
            ->with('application.job')
            ->get();

        return view('admin.pages.interviews.index', compact(
            'pendingInterviews', 
            'acceptedInterviews', 
            'postponedInterviews', 
            'rejectedInterviews', 
            'activeTab'
        ));
    }
    

    public function show()
{}



    /**
     * Store a newly created interview and update the application status.
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $validatedData = $request->validate([
            'application_id'    => 'required|exists:applications,id',
            'interview_start'   => 'required|date',
            'interview_end'     => 'required|date|after:interview_start',
            'interview_link'    => 'required|url',
            'interview_status'  => 'required|in:accepted,rejected',
            'interview_remarks' => 'nullable|string|max:255'
        ]);

        // Update the related application status to "accepted" if the interview outcome is accepted.
        $application = Application::findOrFail($validatedData['application_id']);

        $application->update(['status' => 'accepted']);

     
        Interview::create([
            'application_id'  => $validatedData['application_id'],
            'interview_start' => $validatedData['interview_start'],
            'interview_end'   => $validatedData['interview_end'],
            'interview_link'  => $validatedData['interview_link'],
            'status'          => $validatedData['interview_status'],
            'remarks'         => $validatedData['interview_remarks'] ?? null,
        ]);

        return redirect()->route('admin.applications.show', $application->id)
                         ->with('success', 'Application approved and interview scheduled successfully.');
    }




    public function update(Request $request, $id)
{
    $interview = Interview::findOrFail($id);

    // Validate common inputs
    $rules = [
        'status' => 'required|in:accepted,rejected,postponed,pending',
    ];

    // If postponement, we need new start, new end, new link.
    if($request->status == 'postponed') {
        $rules['interview_start'] = 'required|date';
        $rules['interview_end'] = 'required|date|after:interview_start';
        $rules['interview_link'] = 'required|url';
    }
    // If rejection, require remarks.
    if($request->status == 'rejected') {
        $rules['remarks'] = 'required|string|max:255';
    }
    
    $validatedData = $request->validate($rules);

    // Update interview record. For postponement, update all fields.
    $interview->update($validatedData);

    return redirect()->route('admin.interviews.index', ['tab' => 'pending'])
                     ->with('success', 'Interview updated successfully.');
}


    // Other methods (index, show, edit, update, destroy) may be implemented as needed.
}
