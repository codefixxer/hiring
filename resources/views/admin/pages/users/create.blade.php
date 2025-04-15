@extends('admin.layouts.app')

@section('content')
<div class="container my-4">
   <div class="card">
      <div class="card-header">
         <h5>{{ isset($user) ? 'Edit User' : 'Create User' }}</h5>
      </div>
      <div class="card-body">
         <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}">
             @csrf
             @if(isset($user))
                 @method('PUT')
             @endif

             <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
             </div>

             <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
             </div>

             <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" id="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
                  @if(isset($user))
                      <small class="text-muted">Leave blank if you don't want to change the password.</small>
                  @endif
             </div>

             <div class="mb-3">
                   <label for="role" class="form-label">Role</label>
                   <select name="role" id="role" class="form-select" required>
                       <option value="">Select Role</option>
                       <option value="employer" {{ old('role', isset($user) ? $user->role : '') == 'employer' ? 'selected' : '' }}>Employer</option>
                       <option value="agent" {{ old('role', isset($user) ? $user->role : '') == 'agent' ? 'selected' : '' }}>Agent</option>
                       <option value="employee" {{ old('role', isset($user) ? $user->role : '') == 'employee' ? 'selected' : '' }}>Employee</option>
                   </select>
             </div>

             <div class="d-flex gap-3">
                 <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update' : 'Submit' }}</button>
                 <button type="reset" class="btn btn-secondary">Reset</button>
             </div>
         </form>
      </div>
   </div>
</div>
@endsection
