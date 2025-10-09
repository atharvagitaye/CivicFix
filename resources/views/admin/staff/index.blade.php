@extends('layouts.app')

@section('title', 'Staff Management - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people"></i> Staff Management</h2>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Staff Member
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Staff Members</h5>
        </div>
        <div class="card-body">
            @if($staffMembers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Department</th>
                                <th>Assigned Issues</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffMembers as $staff)
                                <tr>
                                    <td><strong>#{{ $staff->id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    {{ strtoupper(substr($staff->user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <strong>{{ $staff->user->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $staff->user->email }}</td>
                                    <td>{{ $staff->phone ?? 'Not provided' }}</td>
                                    <td>{{ $staff->department ?? 'Not assigned' }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $staff->issue_assignments_count ?? 0 }} issues
                                        </span>
                                    </td>
                                    <td>{{ $staff->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.staff.show', $staff) }}" 
                                               class="btn btn-outline-primary btn-sm" 
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('admin.staff.destroy', $staff) }}" 
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to remove this staff member? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm" 
                                                        title="Remove Staff">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people fs-1 text-muted"></i>
                    <h5 class="text-muted mt-3">No Staff Members Found</h5>
                    <p class="text-muted">Add staff members to manage issues efficiently.</p>
                    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add First Staff Member
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection