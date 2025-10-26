@extends('layouts.app')

@section('title', 'All Issues - CivicFix Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-list-task"></i> All Issues
                </h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.issues.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="">All Priorities</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
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
                                <a href="{{ route('admin.issues.index') }}" class="btn btn-outline-secondary">Clear Filters</a>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Issues ({{ $issues->total() }} total)</h5>
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-success btn-sm" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($issues->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Reporter</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Assigned To</th>
                                        <th>Reported</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($issues as $issue)
                                        <tr>
                                            <td><strong>#{{ $issue->id }}</strong></td>
                                            <td>
                                                <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none">
                                                    {{ Str::limit($issue->title, 50) }}
                                                </a>
                                                @if($issue->media->count() > 0)
                                                    <i class="bi bi-paperclip text-muted ms-1" title="Has attachments"></i>
                                                @endif
                                            </td>
                                            <td>{{ $issue->user->name }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $issue->category->name }}</span>
                                                @if($issue->subcategory)
                                                    <br><small class="text-muted">{{ $issue->subcategory->name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'submitted' => 'primary',
                                                        'in_progress' => 'warning',
                                                        'resolved' => 'success'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$issue->status] ?? 'secondary' }}">
                                                    @if($issue->status === 'submitted')
                                                        Open
                                                    @else
                                                        {{ ucwords(str_replace('_', ' ', $issue->status)) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $priorityColors = [
                                                        'low' => 'success',
                                                        'medium' => 'warning',
                                                        'high' => 'danger',
                                                        'urgent' => 'dark'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $priorityColors[$issue->priority] ?? 'secondary' }}">
                                                    {{ ucfirst($issue->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($issue->assignment && $issue->assignment->staff)
                                                    <span class="badge bg-info">{{ $issue->assignment->staff->user->name }}</span>
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $issue->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('issues.show', $issue) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if(!$issue->assignment)
                                                        <button class="btn btn-outline-warning btn-sm assign-issue" 
                                                                data-issue-id="{{ $issue->id }}"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#assignModal">
                                                            <i class="bi bi-person-plus"></i>
                                                        </button>
                                                    @endif
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                                type="button" 
                                                                data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="{{ route('issues.show', $issue) }}">
                                                                <i class="bi bi-eye"></i> View Details
                                                            </a></li>
                                                            @if($issue->status != 'resolved')
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item update-status" 
                                                                       href="#" 
                                                                       data-issue-id="{{ $issue->id }}"
                                                                       data-current-status="{{ $issue->status }}">
                                                                    <i class="bi bi-arrow-repeat"></i> Update Status
                                                                </a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center pt-3">
                            {{ $issues->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-list-task fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No issues found</h5>
                            <p class="text-muted">Try adjusting your filters or search terms.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assignment Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Issue to Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="assignForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="assignIssueId" name="issue_id">
                    <div class="mb-3">
                        <label for="staffSelect" class="form-label">Select Staff Member</label>
                        <select id="staffSelect" name="staff_id" class="form-select" required>
                            <option value="">Choose staff member...</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assignmentNotes" class="form-label">Assignment Notes</label>
                        <textarea id="assignmentNotes" name="notes" class="form-control" rows="3" placeholder="Optional notes for the assigned staff member..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Issue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle issue assignment
    document.querySelectorAll('.assign-issue').forEach(button => {
        button.addEventListener('click', function() {
            const issueId = this.dataset.issueId;
            document.getElementById('assignIssueId').value = issueId;
        });
    });

    // Handle assignment form submission
    document.getElementById('assignForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const issueId = formData.get('issue_id');
        
        fetch(`/admin/issues/${issueId}/assign`, {
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
                location.reload();
            } else {
                alert('Failed to assign issue. Please try again.');
            }
        })
        .catch(error => {
            alert('An error occurred. Please try again.');
        });
    });

    // Handle status updates
    document.querySelectorAll('.update-status').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const issueId = this.dataset.issueId;
            const currentStatus = this.dataset.currentStatus;
            
            const statusOptions = ['submitted', 'in_progress', 'resolved'];
            const statusLabels = {
                'submitted': 'Open',
                'in_progress': 'In Progress', 
                'resolved': 'Resolved'
            };
            
            let options = statusOptions
                .filter(status => status !== currentStatus)
                .map(status => `<option value="${status}">${statusLabels[status]}</option>`)
                .join('');
                
            const newStatus = prompt(`Current status: ${statusLabels[currentStatus]}\n\nSelect new status:\n${statusOptions.filter(s => s !== currentStatus).map((s, i) => `${i+1}. ${statusLabels[s]}`).join('\n')}\n\nEnter number (1-${statusOptions.length-1}):`);
            
            if (newStatus && newStatus >= 1 && newStatus <= statusOptions.length-1) {
                const selectedStatus = statusOptions.filter(s => s !== currentStatus)[newStatus-1];
                
                fetch(`/admin/issues/${issueId}/status`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: selectedStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to update status. Please try again.');
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                });
            }
        });
    });
});
</script>
@endsection