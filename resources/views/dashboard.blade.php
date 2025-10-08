@extends('layouts.app')

@section('title', 'Dashboard - CivicFix')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-speedometer2"></i> Welcome, {{ Auth::user()->name }}!
                </h1>
                <a href="{{ route('issues.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Report New Issue
                </a>
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
                            <h4 class="card-title">{{ $stats['total_reported'] }}</h4>
                            <p class="card-text">Total Reported</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['open_issues'] }}</h4>
                            <p class="card-text">Open Issues</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-hourglass-split fs-1"></i>
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
                            <h4 class="card-title">{{ $stats['in_progress_issues'] }}</h4>
                            <p class="card-text">In Progress</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-gear fs-1"></i>
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
                            <h4 class="card-title">{{ $stats['resolved_issues'] }}</h4>
                            <p class="card-text">Resolved</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Issues -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Your Recent Issues
                    </h5>
                    <a href="{{ route('issues.index') }}" class="btn btn-outline-light btn-sm">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentIssues->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentIssues as $issue)
                                        <tr>
                                            <td>
                                                <strong>{{ $issue->title }}</strong>
                                                @if($issue->priority === 'High')
                                                    <span class="badge bg-danger ms-2">High Priority</span>
                                                @elseif($issue->priority === 'Medium')
                                                    <span class="badge bg-warning ms-2">Medium Priority</span>
                                                @endif
                                            </td>
                                            <td>{{ $issue->category->name }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($issue->status === 'submitted') bg-primary
                                                    @elseif($issue->status === 'in_progress') bg-warning
                                                    @elseif($issue->status === 'resolved') bg-success
                                                    @elseif($issue->status === 'closed') bg-secondary
                                                    @else bg-info
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($issue->assignment && $issue->assignment->staff)
                                                    {{ $issue->assignment->staff->user->name }}
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $issue->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('issues.show', $issue) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No issues reported yet</h5>
                            <p class="text-muted">Start by reporting your first infrastructure issue!</p>
                            <a href="{{ route('issues.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Report Issue
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection