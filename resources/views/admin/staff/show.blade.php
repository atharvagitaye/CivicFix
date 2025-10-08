@extends('layouts.app')

@section('title', 'Staff Details: {{ $staff->user->name }} - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-badge"></i> Staff Details: {{ $staff->user->name }}</h2>
        <div class="btn-group">
            <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-outline-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Staff
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Staff Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Staff Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-lg mx-auto mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ strtoupper(substr($staff->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <h5>{{ $staff->user->name }}</h5>
                        <p class="text-muted">{{ $staff->user->email }}</p>
                    </div>

                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Staff ID:</strong></td>
                            <td>#{{ $staff->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Employee ID:</strong></td>
                            <td>{{ $staff->employee_id ?? 'Not set' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Department:</strong></td>
                            <td>{{ $staff->department ?? 'Not assigned' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td>{{ $staff->phone ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Joined:</strong></td>
                            <td>{{ $staff->created_at->format('F d, Y') }}</td>
                        </tr>
                    </table>

                    @if($staff->address)
                        <div class="mt-3">
                            <strong>Address:</strong>
                            <p class="text-muted">{{ $staff->address }}</p>
                        </div>
                    @endif

                    @if($staff->bio)
                        <div class="mt-3">
                            <strong>Bio / Notes:</strong>
                            <p class="text-muted">{{ $staff->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Assignment Statistics -->
        <div class="col-lg-8">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-primary">{{ $stats['total_assigned'] }}</h3>
                            <p class="text-muted mb-0">Total Assigned</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-warning">{{ $stats['in_progress'] }}</h3>
                            <p class="text-muted mb-0">In Progress</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-success">{{ $stats['resolved'] }}</h3>
                            <p class="text-muted mb-0">Resolved</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-info">{{ $stats['pending'] }}</h3>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Assignments -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Assignments</h5>
                </div>
                <div class="card-body">
                    @if($recentAssignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Issue ID</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Assigned</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAssignments as $assignment)
                                        <tr>
                                            <td><strong>#{{ $assignment->issue->id }}</strong></td>
                                            <td>
                                                <strong>{{ \Illuminate\Support\Str::limit($assignment->issue->title, 30) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($assignment->issue->status === 'submitted') bg-primary
                                                    @elseif($assignment->issue->status === 'in_progress') bg-warning
                                                    @elseif($assignment->issue->status === 'resolved') bg-success
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $assignment->issue->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($assignment->issue->priority === 'urgent') bg-danger
                                                    @elseif($assignment->issue->priority === 'high') bg-warning
                                                    @elseif($assignment->issue->priority === 'medium') bg-info
                                                    @else bg-success
                                                    @endif">
                                                    {{ ucfirst($assignment->issue->priority) }}
                                                </span>
                                            </td>
                                            <td>{{ $assignment->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('issues.show', $assignment->issue) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No Assignments</h5>
                            <p class="text-muted">This staff member hasn't been assigned any issues yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection