@extends('admin.layouts.app')

@section('content')
<div class="container my-4">
    <h5 class="mb-3">User Management</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Create New User
    </a>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                     <td>{{ $user->id }}</td>
                     <td>{{ $user->name }}</td>
                     <td>{{ $user->email }}</td>
                     <td>{{ ucfirst($user->role) }}</td>
                     <td class="text-center">
                         <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                             <i class="bi bi-pencil-square"></i> Edit
                         </a>
                         <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="btn btn-sm btn-danger">
                                 <i class="bi bi-trash"></i> Delete
                             </button>
                         </form>
                     </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
