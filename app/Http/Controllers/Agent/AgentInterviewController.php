<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Interview;

class AgentInterviewController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'pending');

        $pendingInterviews   = Interview::with('application.job')
            ->where('status', 'pending')
            ->get();
        $acceptedInterviews  = Interview::with('application.job')
            ->where('status', 'accepted')
            ->get();
        $postponedInterviews = Interview::with('application.job')
            ->where('status', 'postponed')
            ->get();
        $rejectedInterviews  = Interview::with('application.job')
            ->where('status', 'rejected')
            ->get();

        return view('agent.pages.interviews.index', compact(
            'pendingInterviews',
            'acceptedInterviews',
            'postponedInterviews',
            'rejectedInterviews',
            'activeTab'
        ));
    }

    public function show($id)
    {
        $interview = Interview::with(['application.job', 'application.applicant'])
            ->findOrFail($id);

        return view('agent.pages.interviews.show', compact('interview'));
    }




public function edit($id)
{
    $interview = Interview::with(['application.job', 'application.applicant'])
        ->findOrFail($id);

    return view('agent.pages.interviews.create', compact('interview'));
}




    public function update(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        $rules = [
            'status' => 'required|in:pending,accepted,rejected,postponed',
        ];

        if ($request->status === 'postponed') {
            $rules['interview_start'] = 'required|date';
            $rules['interview_end']   = 'required|date|after:interview_start';
            $rules['interview_link']  = 'required|url';
        }

        if ($request->status === 'rejected') {
            $rules['remarks'] = 'required|string|max:255';
        }

        $data = $request->validate($rules);

        $interview->update($data);

        return redirect()
            ->route('agent.interviews.index', ['tab' => 'pending'])
            ->with('success', 'Interview updated successfully.');
    }




    public function destroy($id)
{
    $interview = Interview::findOrFail($id);
    $interview->delete();

    return redirect()
        ->route('agent.interviews.index')
        ->with('success', 'Interview deleted successfully.');
}

}
