<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginform()
    {
        return view('auth.login');
    }

    public function registerform()
    {
        return view('auth.register');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginform');
    }

    public function register(Request $request)
    {
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

        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role'     => 'user',
        ]);

        Auth::login($user, true);
        return redirect()->route('user.dashboard.index');
    }

    public function login(Request $request)
    {
        // 1) validate
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // 2) attempt
        if (! Auth::attempt($request->only('email', 'password'))) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->withInput();
        }

        // 3) regenerate session
        $request->session()->regenerate();

        // 4) redirect by role
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard.index');
            case 'agent':
                return redirect()->route('agent.dashboard.index');
            case 'employer':
                return redirect()->route('employer.dashboard.index');
            case 'user':
            default:
                return redirect()->route('user.dashboard.index');
        }
    }


    public function error403()
{
    return view('auth.errors.error403');
}

}
