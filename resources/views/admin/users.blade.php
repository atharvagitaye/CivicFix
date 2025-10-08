@extends('layouts.app')

@section('title', 'User Management - CivicFix Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-people"></i> User Management
                </h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Users</h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td><strong>#{{ $user->id }}</strong></td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->isAdmin())
                                                    <span class="badge bg-danger">Admin</span>
                                                @elseif($user->isStaff())
                                                    <span class="badge bg-warning">Staff</span>
                                                @else
                                                    <span class="badge bg-primary">User</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(!$user->isAdmin() && !$user->isStaff())
                                                        <!-- Promote to Staff -->
                                                        <form method="POST" action="{{ route('admin.users.promote.staff') }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                            <button type="submit" 
                                                                    class="btn btn-outline-warning btn-sm"
                                                                    onclick="return confirm('Are you sure you want to promote {{ $user->name }} to Staff?')">
                                                                <i class="bi bi-person-workspace"></i> Make Staff
                                                            </button>
                                                        </form>
                                                        
                                                        <!-- Promote to Admin -->
                                                        <form method="POST" action="{{ route('admin.users.promote.admin') }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                            <button type="submit" 
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    onclick="return confirm('Are you sure you want to promote {{ $user->name }} to Admin? This will give them full system access.')">
                                                                <i class="bi bi-shield-check"></i> Make Admin
                                                            </button>
                                                        </form>
                                                    @elseif($user->isStaff() && !$user->isAdmin())
                                                        <!-- Promote Staff to Admin -->
                                                        <form method="POST" action="{{ route('admin.users.promote.admin') }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                            <button type="submit" 
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    onclick="return confirm('Are you sure you want to promote {{ $user->name }} to Admin?')">
                                                                <i class="bi bi-shield-check"></i> Make Admin
                                                            </button>
                                                        </form>
                                                        
                                                        <span class="badge bg-success">Staff Member</span>
                                                    @else
                                                        <span class="badge bg-info">Administrator</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No users found</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Role Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Role Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><span class="badge bg-primary">User</span></h6>
                            <ul class="small">
                                <li>Report infrastructure issues</li>
                                <li>View their own reported issues</li>
                                <li>Add comments and media to their issues</li>
                                <li>Track issue progress</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6><span class="badge bg-warning">Staff</span></h6>
                            <ul class="small">
                                <li>All User permissions</li>
                                <li>View and manage assigned issues</li>
                                <li>Update issue status and progress</li>
                                <li>Add progress notes and updates</li>
                                <li>Mark issues as completed</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6><span class="badge bg-danger">Admin</span></h6>
                            <ul class="small">
                                <li>All Staff permissions</li>
                                <li>Manage all issues in the system</li>
                                <li>Assign issues to staff members</li>
                                <li>Manage categories and sub-categories</li>
                                <li>Promote users to staff or admin</li>
                                <li>View system-wide statistics</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Warning:</strong> Promoting users to Admin gives them full system access. 
                        This action should only be performed for trusted individuals who need administrative privileges.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection