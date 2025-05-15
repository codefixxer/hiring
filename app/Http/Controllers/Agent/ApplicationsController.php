<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;            // ← import Request
use Illuminate\Support\Facades\Auth;

class ApplicationsController extends Controller
{
    /**
     * List all applications assigned to the authenticated agent.
     */
    public function index()
    {
        $agentId = Auth::id();

        $applications = Application::with(['job', 'applicant'])
            ->whereHas('job', function($q) use ($agentId) {
                $q->whereJsonContains('agent_ids', $agentId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agent.pages.applications.index', compact('applications'));
    }

    /**
     * Show a single assigned application.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $application = Application::with(['job', 'applicant'])
            ->findOrFail($id);

        return view('agent.pages.applications.show', compact('application'));
    }

    /**
     * Approve an application.
     *
     * @param  int  $id
     */
    public function approve($id)
    {
        $app = Application::findOrFail($id);
        $app->update(['status' => 'accepted']);

        return redirect()
            ->route('agent.applications.show', $id)
            ->with('success', 'Application approved.');
    }

    /**
     * Reject an application with remarks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:255',
        ]);

        $app = Application::findOrFail($id);
        $app->update([
            'status'  => 'rejected',
            'remarks' => $request->remarks,
        ]);

        return redirect()
            ->route('agent.applications.show', $id)
            ->with('success', 'Application rejected with remarks.');
    }
}
