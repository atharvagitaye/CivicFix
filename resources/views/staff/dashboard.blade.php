@extends('layouts.app')

@section('title', 'Staff Dashboard - CivicFix')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-person-workspace"></i> Staff Dashboard
                </h1>
                <a href="{{ route('staff.issues') }}" class="btn btn-primary">
                    <i class="bi bi-list-check"></i> View All My Issues
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['assigned_issues'] }}</h4>
                            <p class="card-text">Total Assigned</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clipboard-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['pending_issues'] }}</h4>
                            <p class="card-text">Pending Work</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-hourglass-split fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['completed_issues'] }}</h4>
                            <p class="card-text">Completed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Assigned Issues -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-check"></i> My Recent Assigned Issues
                    </h5>
                    <a href="{{ route('staff.issues') }}" class="btn btn-outline-light btn-sm">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($assignedIssues->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Reporter</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignedIssues as $issue)
                                        <tr>
                                            <td><strong>#{{ $issue->id }}</strong></td>
                                            <td>
                                                <strong>{{ Str::limit($issue->title, 40) }}</strong>
                                                @if($issue->priority === 'High')
                                                    <span class="badge bg-danger ms-2">High Priority</span>
                                                @elseif($issue->priority === 'Medium')
                                                    <span class="badge bg-warning ms-2">Medium Priority</span>
                                                @endif
                                            </td>
                                            <td>{{ $issue->user->name }}</td>
                                            <td>{{ $issue->category->name }}</td>
                                            <td>
                                                <span class="
                                                    @if($issue->priority === 'High') text-danger fw-bold
                                                    @elseif($issue->priority === 'Medium') text-warning
                                                    @else text-success
                                                    @endif">
                                                    {{ $issue->priority }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($issue->status === 'submitted') bg-info
                                                    @elseif($issue->status === 'in_progress') bg-warning
                                                    @elseif($issue->status === 'resolved') bg-success
                                                    @elseif($issue->status === 'closed') bg-secondary
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucwords(str_replace('_', ' ', $issue->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $issue->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('staff.issues.show', $issue) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                    @if($issue->status !== 'closed')
                                                        <button type="button" 
                                                                class="btn btn-outline-success btn-sm"
                                                                onclick="completeIssue({{ $issue->id }})">
                                                            <i class="bi bi-check-circle"></i> Complete
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No issues assigned yet</h5>
                            <p class="text-muted">You'll see your assigned issues here when an admin assigns them to you.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    @if($assignedIssues->count() > 0)
        <div class="row mt-4">
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
                                <a href="{{ route('staff.issues') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-list-check"></i><br>
                                    View All Issues
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-outline-warning w-100" onclick="markAllInProgress()">
                                    <i class="bi bi-play-circle"></i><br>
                                    Start Work on Open Issues
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-outline-success w-100" onclick="showCompletedFilter()">
                                    <i class="bi bi-check-circle"></i><br>
                                    View Completed
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-house"></i><br>
                                    Main Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Complete Issue Modal -->
<div class="modal fade" id="completeIssueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complete Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="completeIssueForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to mark this issue as completed?</p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        This will change the issue status to "Closed" and notify the reporter.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Mark as Completed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function completeIssue(issueId) {
    const form = document.getElementById('completeIssueForm');
    form.action = `/staff/issues/${issueId}/complete`;
    
    const modal = new bootstrap.Modal(document.getElementById('completeIssueModal'));
    modal.show();
}

function markAllInProgress() {
    // This would be implemented to bulk update open issues to in progress
    alert('Feature to be implemented: Mark all open issues as in progress');
}

function showCompletedFilter() {
    // This would filter to show only completed issues
    window.location.href = '{{ route("staff.issues") }}?status=completed';
}
</script>
@endpush