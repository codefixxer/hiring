<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;

class EmployerDashboardController extends Controller
{
    public function index()
    {
        return view('employer.pages.dashboard');
    }
}
