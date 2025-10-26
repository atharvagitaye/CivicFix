@extends('layouts.app')

@section('title', 'Issues - CivicFix')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-exclamation-triangle"></i> 
                    @if(Auth::user()->isAdmin()) 
                        All Issues
                    @elseif(Auth::user()->isStaff()) 
                        My Assigned Issues
                    @else 
                        My Reported Issues
                    @endif
                </h1>
                @if(!Auth::user()->isStaff())
                    <a href="{{ route('issues.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Report New Issue
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($issues->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        @if(Auth::user()->isAdmin())
                                            <th>Reporter</th>
                                            <th>Assigned To</th>
                                        @elseif(Auth::user()->isStaff())
                                            <th>Reporter</th>
                                        @else
                                            <th>Assigned To</th>
                                        @endif
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($issues as $issue)
                                        <tr>
                                            <td><strong>#{{ $issue->id }}</strong></td>
                                            <td>
                                                <strong>{{ Str::limit($issue->title, 40) }}</strong>
                                                @if($issue->priority === 'High')
                                                    <span class="badge bg-danger ms-2">High</span>
                                                @elseif($issue->priority === 'Medium')
                                                    <span class="badge bg-warning ms-2">Medium</span>
                                                @else
                                                    <span class="badge bg-success ms-2">Low</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $issue->category->name }}</small><br>
                                                @if($issue->subcategory)
                                                    {{ $issue->subcategory->name }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="
                                                    @if($issue->priority === 'High') priority-high
                                                    @elseif($issue->priority === 'Medium') priority-medium
                                                    @else priority-low
                                                    @endif">
                                                    {{ $issue->priority }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($issue->status === 'submitted') bg-info
                                                    @elseif($issue->status === 'in_progress') bg-warning
                                                    @elseif($issue->status === 'resolved') bg-success
                                                    @else bg-secondary
                                                    @endif">
                                                    @if($issue->status === 'submitted')
                                                        Open
                                                    @else
                                                        {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                                    @endif
                                                </span>
                                            </td>
                                            @if(Auth::user()->isAdmin())
                                                <td>{{ $issue->user->name }}</td>
                                                <td>
                                                    @if($issue->assignment && $issue->assignment->staff && $issue->assignment->staff->user)
                                                        {{ $issue->assignment->staff->user->name }}
                                                    @else
                                                        <span class="text-muted">Unassigned</span>
                                                    @endif
                                                </td>
                                            @elseif(Auth::user()->isStaff())
                                                <td>{{ $issue->user->name }}</td>
                                            @else
                                                <td>
                                                    @if($issue->assignment && $issue->assignment->staff && $issue->assignment->staff->user)
                                                        {{ $issue->assignment->staff->user->name }}
                                                    @else
                                                        <span class="text-muted">Unassigned</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $issue->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('issues.show', $issue) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if(Auth::user()->isAdmin() || (!Auth::user()->isStaff() && $issue->user_id === Auth::id()))
                                                        <a href="{{ route('issues.edit', $issue) }}" 
                                                           class="btn btn-outline-secondary btn-sm">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endif
                                                    @if(Auth::user()->isAdmin() && !$issue->assignedStaff)
                                                        <a href="{{ route('admin.issues.assign', $issue) }}" 
                                                           class="btn btn-outline-info btn-sm">
                                                            <i class="bi bi-person-plus"></i>
                                                        </a>
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
                            {{ $issues->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No issues found</h5>
                            @if(!Auth::user()->isStaff())
                                <p class="text-muted">Start by reporting your first infrastructure issue!</p>
                                <a href="{{ route('issues.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Report Issue
                                </a>
                            @else
                                <p class="text-muted">No issues have been assigned to you yet.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection