@extends('layouts.app')

@section('title', 'My Assigned Issues - CivicFix Staff')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-list-check"></i> My Assigned Issues
                </h1>
                <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-primary">{{ $issues->where('status', 'submitted')->count() }}</h4>
                    <p class="text-muted mb-0">New Issues</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-warning">{{ $issues->where('status', 'in_progress')->count() }}</h4>
                    <p class="text-muted mb-0">In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-success">{{ $issues->where('status', 'resolved')->count() }}</h4>
                    <p class="text-muted mb-0">Resolved</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-info">{{ $issues->count() }}</h4>
                    <p class="text-muted mb-0">Total Assigned</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('staff.issues') }}">>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="">All Priorities</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search issues..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                                <a href="{{ route('staff.issues') }}" class="btn btn-outline-secondary">Clear Filters</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Issues List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">My Issues ({{ $filteredIssues->count() }} shown)</h5>
                </div>
                <div class="card-body p-0">
                    @if($filteredIssues->count() > 0)
                        @foreach($filteredIssues as $issue)
                            <div class="card mb-3 mx-3 mt-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="card-title">
                                                        <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none">
                                                            #{{ $issue->id }} - {{ $issue->title }}
                                                        </a>
                                                    </h6>
                                                    <p class="card-text text-muted mb-2">
                                                        {{ Str::limit($issue->description, 150) }}
                                                    </p>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <span class="badge bg-light text-dark">{{ $issue->category->name }}</span>
                                                        @if($issue->subcategory)
                                                            <span class="badge bg-light text-dark">{{ $issue->subcategory->name }}</span>
                                                        @endif
                                                        @if($issue->media->count() > 0)
                                                            <span class="badge bg-info">
                                                                <i class="bi bi-paperclip"></i> {{ $issue->media->count() }} file(s)
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-end">
                                                @php
                                                    $statusColors = [
                                                        'submitted' => 'primary',
                                                        'in_progress' => 'warning',
                                                        'resolved' => 'success'
                                                    ];
                                                    $priorityColors = [
                                                        'low' => 'success',
                                                        'medium' => 'warning',
                                                        'high' => 'danger',
                                                        'urgent' => 'dark'
                                                    ];
                                                @endphp
                                                <div class="mb-2">
                                                    <span class="badge bg-{{ $statusColors[$issue->status] ?? 'secondary' }}">
                                                        {{ ucwords(str_replace('_', ' ', $issue->status)) }}
                                                    </span>
                                                    <span class="badge bg-{{ $priorityColors[$issue->priority] ?? 'secondary' }}">
                                                        {{ ucfirst($issue->priority) }} Priority
                                                    </span>
                                                </div>
                                                <div class="text-muted small mb-2">
                                                    Reported: {{ $issue->created_at->format('M d, Y') }}<br>
                                                    Assigned: {{ $issue->assignment->created_at->format('M d, Y') }}
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('issues.show', $issue) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                    @if($issue->status != 'resolved')
                                                        <button class="btn btn-outline-success btn-sm update-status" 
                                                                data-issue-id="{{ $issue->id }}"
                                                                data-current-status="{{ $issue->status }}">
                                                            <i class="bi bi-arrow-repeat"></i> Update
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($issue->assignment->notes)
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <strong>Assignment Notes:</strong> {{ $issue->assignment->notes }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-list-check fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No assigned issues found</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['status', 'priority', 'search']))
                                    Try adjusting your filters to see more issues.
                                @else
                                    You don't have any issues assigned to you yet.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Issue Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="statusIssueId" name="issue_id">
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">New Status</label>
                        <select id="newStatus" name="status" class="form-select" required>
                            <option value="">Select status...</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="statusNotes" class="form-label">Update Notes</label>
                        <textarea id="statusNotes" name="notes" class="form-control" rows="3" placeholder="Describe what you've done or the current status..." required></textarea>
                        <div class="form-text">Explain the progress made or resolution details.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));

    // Handle status update button clicks
    document.querySelectorAll('.update-status').forEach(button => {
        button.addEventListener('click', function() {
            const issueId = this.dataset.issueId;
            const currentStatus = this.dataset.currentStatus;
            
            document.getElementById('statusIssueId').value = issueId;
            
            // Show appropriate status options based on current status
            const statusSelect = document.getElementById('newStatus');
            statusSelect.innerHTML = '<option value="">Select status...</option>';
            
            if (currentStatus === 'submitted') {
                statusSelect.innerHTML += '<option value="in_progress">In Progress</option>';
                statusSelect.innerHTML += '<option value="resolved">Resolved</option>';
            } else if (currentStatus === 'in_progress') {
                statusSelect.innerHTML += '<option value="resolved">Resolved</option>';
            }
            
            statusModal.show();
        });
    });

    // Handle status form submission
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const issueId = formData.get('issue_id');
        
        fetch(`/staff/issues/${issueId}/update`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusModal.hide();
                location.reload();
            } else {
                alert('Failed to update status. Please try again.');
            }
        })
        .catch(error => {
            alert('An error occurred. Please try again.');
        });
    });
});
</script>
@endsection