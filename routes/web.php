<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;

// Admin
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Employer\JobController;

// User
use App\Http\Controllers\User\UserJobController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Agent\AgentDashboardController;

// Employer
use App\Http\Controllers\Agent\AgentInterviewController;
use App\Http\Controllers\User\UserApplicationController;
use App\Http\Controllers\Employer\EmployerDashboardController;
use App\Http\Controllers\Employer\EmployerInterviewController;

// Agent
use App\Http\Controllers\Employer\EmployerApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Agent\ApplicationsController as AgentApplicationsController;

// 1. Authentication Routes
Route::get('/', [AuthController::class, 'loginform'])->name('loginform');
Route::get('/login', [AuthController::class, 'loginform'])->name('loginform');
Route::get('/register', [AuthController::class, 'registerform'])->name('registerform');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// 2. Social Login Routes
Route::get('login/google',   [SocialController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback',   [SocialController::class, 'handleGoogleCallback']);
Route::get('login/facebook', [SocialController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('login/facebook/callback', [SocialController::class, 'handleFacebookCallback']);

// 3. Error & Fallback
Route::get('/error',   [AuthController::class, 'error403'])->name('auth.errors.error403');
Route::fallback(fn() => redirect()->route('loginform'));

// 4. Authenticated & Role-Based Routes
Route::middleware('auth')->group(function () {

    // Admin
    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
        Route::resource('dashboard', AdminDashboardController::class);
        Route::resource('users',     UserController::class);
    });

    // User
    Route::prefix('user')->middleware('role:user')->name('user.')->group(function () {
        Route::resource('dashboard', UserDashboardController::class);
        Route::get('jobs',           [UserJobController::class, 'index'])->name('jobs.index');
        Route::get('jobs/{job}',     [UserJobController::class, 'show' ])->name('jobs.show');
        Route::get('applications',    [UserApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/create', [UserApplicationController::class, 'create'])->name('applications.create');
        Route::post('applications',       [UserApplicationController::class, 'store'])->name('applications.store');
        Route::get('applications/{id}',   [UserApplicationController::class, 'show' ])->name('applications.show');
    });

    // Employer
    Route::prefix('employer')->middleware('role:employer')->name('employer.')->group(function () {
        // Dashboard & Jobs
        Route::resource('dashboard', EmployerDashboardController::class);
        Route::resource('jobs',      JobController::class);

        // Applications: finalize first
        Route::get('applications/shortlisted', [EmployerApplicationController::class, 'shortlistedcandidates'])
            ->name('applications.shortlisted');
        Route::patch('applications/{id}/confirm',     [EmployerApplicationController::class, 'confirmCandidate'])
            ->name('applications.confirmCandidate');
        Route::patch('applications/{id}/reject-final',[EmployerApplicationController::class, 'rejectCandidate'])
            ->name('applications.rejectCandidate');

        // Standard CRUD + approve/reject
        Route::resource('applications', EmployerApplicationController::class)
             ->whereNumber('application');
        Route::patch('applications/{application}/approve', [EmployerApplicationController::class, 'approve'])
             ->name('applications.approve');
        Route::patch('applications/{application}/reject',  [EmployerApplicationController::class, 'reject'])
             ->name('applications.reject');

        // Interviews
        Route::get(   'interviews',      [EmployerInterviewController::class, 'index']) ->name('interviews.index');
        Route::post(  'interviews',      [EmployerInterviewController::class, 'store']) ->name('interviews.store');
     });

    // Agent
    Route::prefix('agent')->middleware('role:agent')->name('agent.')->group(function () {
        Route::resource('dashboard', AgentDashboardController::class);
        Route::get('applications',       [AgentApplicationsController::class, 'index'])->name('applications.index');
        Route::get('applications/{id}',   [AgentApplicationsController::class, 'show' ])->name('applications.show');
        Route::patch('applications/{id}/approve', [AgentApplicationsController::class, 'approve'])->name('applications.approve');
        Route::patch('applications/{id}/reject',  [AgentApplicationsController::class, 'reject' ])->name('applications.reject');






// Interviews
        Route::get(   'interviews',      [AgentInterviewController::class, 'index']) ->name('interviews.index');
        Route::get(   'interviews/create',      [AgentInterviewController::class, 'edit']) ->name('interviews.edit');
        Route::get(   'interviews/show/{id}', [AgentInterviewController::class, 'show'])  ->name('interviews.show');
        Route::patch( 'interviews/{id}', [AgentInterviewController::class, 'update'])->name('interviews.update');
          Route::post( 'interviews/{id}/delete', [AgentInterviewController::class, 'destroy'])->name('interviews.destroy');

    });

});
