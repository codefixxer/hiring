<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginform');
    }


    public function loginform()
    {
        return view('auth.login');
    }


    public function registerform()
    {
        return view('auth.register');
    }
    public function error403()
    {
        return view('auth.errors.error403');
    }







    



    public function register(Request $request)
    {
        // … your Google/Facebook blocks remain unchanged …
    
        // Manual registration (non‑Google, non‑Facebook)
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Create the user
        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
    
        // Auto‑login the newly registered user
        Auth::login($user, true);
    
        // Redirect to your dashboard route
        return redirect()->route('user.dashboard');
    }















    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard.index');
            } else {
                return redirect()->route('user.dashboard.index');
            }
        } else {
            return back()->withErrors(['email' => 'The provided credentials are incorrect.'])->withInput();
        }
    }
}
