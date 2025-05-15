<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use Illuminate\Http\Request;

class EmployerInterviewController extends Controller
{
    
 public function store(Request $request)
    {
        $data = $request->validate([
            'application_id'    => 'required|exists:applications,id',
            'interview_start'   => 'required|date',
            'interview_end'     => 'required|date|after:interview_start',
            'interview_link'    => 'required|url',
            'interview_remarks' => 'nullable|string|max:255',
        ]);

        // Mark the application as accepted in the workflow
        $application = Application::findOrFail($data['application_id']);
        $application->update(['status' => 'confirmed']);

        // Create the interview record with status = "pending"
        Interview::create([
            'application_id'  => $data['application_id'],
            'interview_start' => $data['interview_start'],
            'interview_end'   => $data['interview_end'],
            'interview_link'  => $data['interview_link'],
            'status'          => 'pending',
            'remarks'         => $data['interview_remarks'] ?? null,
        ]);

        return redirect()
            ->route('employer.applications.shortlisted', $application->id)
            ->with('success', 'Interview scheduled (status: pending) & application accepted.');
    }





 






}
