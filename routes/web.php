<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Chatbot\ChatbotController;
use App\Http\Controllers\Chatbot\UserNameController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'loginform'])->name('loginform');
Route::get('/register', [AuthController::class, 'registerform'])->name('registerform');
Route::post('register', [AuthController::class, 'register'])->name('register');;
Route::post('login', [AuthController::class, 'login'])->name('login');;

Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::get('login/google', [SocialController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [SocialController::class, 'handleGoogleCallback']);

Route::get('login/facebook', [SocialController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('login/facebook/callback', [SocialController::class, 'handleFacebookCallback']);



Route::get('/error', [AuthController::class, 'error403'])->name('auth.errors.error403');























Route::middleware(['auth'])->group(function () {


    Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {




    Route::resource('dashboard', DashboardController::class);
    Route::resource('users', UserController::class);

});



















Route::prefix('user')->middleware(['role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
});


});





