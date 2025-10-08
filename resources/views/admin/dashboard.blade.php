@extends('layouts.app')

@section('title', 'Admin Dashboard - CivicFix')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-gear"></i> Admin Dashboard
                </h1>
                <div>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-tags"></i> Manage Categories
                    </a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-people"></i> Manage Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['total_issues'] }}</h4>
                            <p class="card-text">Total Issues</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['unassigned_issues'] }}</h4>
                            <p class="card-text">Unassigned Issues</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-x fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['total_staff'] }}</h4>
                            <p class="card-text">Staff Members</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-workspace fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['total_users'] }}</h4>
                            <p class="card-text">Total Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Unassigned Issues -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-person-x"></i> Unassigned Issues Requiring Attention
                    </h5>
                    <a href="{{ route('admin.issues.index') }}" class="btn btn-outline-light btn-sm">
                        View All Issues
                    </a>
                </div>
                <div class="card-body">
                    @if($unassignedIssues->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Reporter</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unassignedIssues as $issue)
                                        <tr>
                                            <td><strong>#{{ $issue->id }}</strong></td>
                                            <td>
                                                <strong>{{ \Illuminate\Support\Str::limit($issue->title, 30) }}</strong>
                                                @if($issue->priority === 'urgent')
                                                    <span class="badge bg-danger ms-1">Urgent</span>
                                                @elseif($issue->priority === 'high')
                                                    <span class="badge bg-warning ms-1">High</span>
                                                @elseif($issue->priority === 'medium')
                                                    <span class="badge bg-info ms-1">Medium</span>
                                                @endif
                                            </td>
                                            <td>{{ $issue->user->name }}</td>
                                            <td>{{ $issue->category->name }}</td>
                                            <td>
                                                <span class="
                                                    @if($issue->priority === 'urgent') text-danger fw-bold
                                                    @elseif($issue->priority === 'high') text-warning fw-bold
                                                    @elseif($issue->priority === 'medium') text-info
                                                    @else text-success
                                                    @endif">
                                                    {{ ucfirst($issue->priority) }}
                                                </span>
                                            </td>
                                            <td>{{ $issue->created_at->format('M d') }}</td>
                                            <td>
                                                <a href="{{ route('admin.issues.assign', $issue) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-person-plus"></i> Assign
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle fs-1 text-success"></i>
                            <h6 class="text-muted mt-2">All issues are assigned!</h6>
                            <p class="text-muted small">Great job managing the workload.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Issues by Category Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart"></i> Issues by Category
                    </h5>
                </div>
                <div class="card-body">
                    @if($issuesByCategory->count() > 0)
                        @foreach($issuesByCategory as $category)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>{{ $category->name }}</strong>
                                    <small class="text-muted d-block">{{ $category->issues_count }} issues</small>
                                </div>
                                <div>
                                    <div class="progress" style="width: 100px; height: 20px;">
                                        <div class="progress-bar" 
                                             style="width: {{ $issuesByCategory->max('issues_count') > 0 ? ($category->issues_count / $issuesByCategory->max('issues_count')) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-pie-chart fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No issues reported yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle"></i><br>
                                Add New Category
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-person-plus"></i><br>
                                Promote Users
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.issues.index') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-list-check"></i><br>
                                Manage All Issues
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-people"></i><br>
                                Manage Staff
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection