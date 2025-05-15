<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class UserJobController extends Controller
{
    public function index()
    {
        $jobs = Job::where('status', 'active')->get();
        return view('user.pages.jobs.index', compact('jobs'));
    }

    public function show($id)
    {
        $job = Job::findOrFail($id);
        return view('user.pages.jobs.show', compact('job'));
    }
}
