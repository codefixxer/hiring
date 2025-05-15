<?php
// app/Http/Controllers/Employer/EmployerApplicationController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class EmployerApplicationController extends Controller
{
    /**
     * Display a listing of all applications (standard CRUD index).
     */
    public function index()
    {
        $applications = Application::with(['job','applicant'])
            ->orderBy('created_at','desc')
            ->get();

        return view('employer.pages.applications.index', compact('applications'));
    }

    /**
     * Show a single application.
     */
    public function show($id)
    {
        $application = Application::with(['job','applicant'])
            ->findOrFail($id);

        return view('employer.pages.applications.show', compact('application'));
    }

    /**
     * “Approve” (move to accepted) in the normal workflow.
     */
    public function approve($id)
    {
        $app = Application::findOrFail($id);
        $app->update(['status' => 'accepted']);

        return redirect()
            ->route('employer.applications.show', $id)
            ->with('success', 'Application accepted.');
    }

    /**
     * “Reject” in the normal workflow.
     */
    public function reject(Request $request, $id)
    {
        // if you want to collect remarks here, validate:
        $request->validate([
            'remarks' => 'nullable|string|max:255',
        ]);

        $app = Application::findOrFail($id);
        $app->update([
            'status'  => 'rejected',
            'remarks' => $request->input('remarks'),
        ]);

        return redirect()
            ->route('employer.applications.show', $id)
            ->with('success', 'Application rejected.');
    }

    /**
     * Show only those already “accepted” so the employer can make a final hire call.
     */
    public function shortlistedcandidates()
    {
        $applications = Application::with(['job','applicant'])
            ->where('status', 'accepted')
            ->orderBy('created_at','desc')
            ->get();

        return view('employer.pages.applications.shortlistedcandidates', compact('applications'));
    }

    /**
     * Final confirm (hire) a candidate.
     */
    public function confirmCandidate($id)
    {
        $app = Application::findOrFail($id);
        $app->update(['status' => 'confirmed']);

        return redirect()
            ->route('employer.applications.shortlisted')
            ->with('success', 'Candidate confirmed successfully.');
    }

    /**
     * Final reject a candidate.
     */
    public function rejectCandidate($id)
    {
        $app = Application::findOrFail($id);
        $app->update(['status' => 'rejected']);

        return redirect()
            ->route('employer.applications.shortlisted')
            ->with('success', 'Candidate rejected.');
    }





      public function schedule($id)
    {
        $application = Application::with(['job','applicant'])
            ->findOrFail($id);

        return view('employer.pages.applications.scheduleInterview', compact('application'));
    }
}
