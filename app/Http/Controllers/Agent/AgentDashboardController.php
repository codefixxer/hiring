<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;

class AgentDashboardController extends Controller
{
    public function index()
    {
        return view('agent.pages.dashboard');
    }
}
