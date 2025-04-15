<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    /**
     * Show the form for creating a new resource.
     */


    public function index()
    {
         $users = User::all();
         return view('admin.pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
         return view('admin.pages.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
         $data = $request->validate([
             'name'     => 'required|string|max:255',
             'email'    => 'required|email|unique:users,email',
             'password' => 'required|string|min:6',
             'role'     => 'required|in:employer,agent,employee'
         ]);

         // Hash the password before storing
         $data['password'] = Hash::make($data['password']);

         User::create($data);

         return redirect()->route('admin.users.index')
                          ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
         $user = User::findOrFail($id);
         return view('admin.pages.users.create', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
         $user = User::findOrFail($id);
         $data = $request->validate([
             'name'     => 'required|string|max:255',
             'email'    => 'required|email|unique:users,email,'.$user->id,
             'password' => 'nullable|string|min:6',
             'role'     => 'required|in:employer,agent,employee'
         ]);

         // Hash the password only if a new one is provided.
         if (!empty($data['password'])) {
             $data['password'] = Hash::make($data['password']);
         } else {
             unset($data['password']);
         }

         $user->update($data);

         return redirect()->route('admin.users.index')
                          ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
         $user = User::findOrFail($id);
         $user->delete();
         return redirect()->route('admin.users.index')
                          ->with('success', 'User deleted successfully.');
    }
}
