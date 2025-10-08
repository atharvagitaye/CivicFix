@extends('layouts.app')

@section('title', 'Category Details: {{ $category->name }} - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-folder"></i> Category: {{ $category->name }}</h2>
        <div class="btn-group">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.categories.sub-categories', $category) }}" class="btn btn-outline-secondary">
                <i class="bi bi-collection"></i> Manage Sub-Categories
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Categories
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
        <!-- Category Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Category Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Description:</strong></td>
                            <td>{{ $category->description ?? 'No description provided' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Issues:</strong></td>
                            <td>
                                <span class="badge bg-primary">{{ $category->issues_count }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Sub-Categories:</strong></td>
                            <td>
                                <span class="badge bg-info">{{ $category->sub_categories_count ?? 0 }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>{{ $category->created_at->format('F d, Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Last Updated:</strong></td>
                            <td>{{ $category->updated_at->format('F d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sub-Categories -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sub-Categories</h5>
                    <a href="{{ route('admin.categories.sub-categories', $category) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Manage Sub-Categories
                    </a>
                </div>
                <div class="card-body">
                    @if($category->subCategories->count() > 0)
                        <div class="row">
                            @foreach($category->subCategories as $subCategory)
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $subCategory->name }}</h6>
                                            @if($subCategory->description)
                                                <p class="card-text small text-muted">{{ $subCategory->description }}</p>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    {{ $subCategory->issues_count ?? 0 }} issues
                                                </small>
                                                <small class="text-muted">
                                                    Created {{ $subCategory->created_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-folder-x fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No Sub-Categories</h5>
                            <p class="text-muted">This category doesn't have any sub-categories yet.</p>
                            <a href="{{ route('admin.categories.sub-categories', $category) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Sub-Categories
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Issues -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Issues in this Category</h5>
                    <a href="{{ route('admin.issues.index') }}?category={{ $category->id }}" class="btn btn-outline-primary btn-sm">
                        View All Issues
                    </a>
                </div>
                <div class="card-body">
                    @if($recentIssues->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Sub-Category</th>
                                        <th>Reporter</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentIssues as $issue)
                                        <tr>
                                            <td><strong>#{{ $issue->id }}</strong></td>
                                            <td>{{ \Illuminate\Support\Str::limit($issue->title, 30) }}</td>
                                            <td>{{ $issue->subcategory->name ?? 'None' }}</td>
                                            <td>{{ $issue->user->name }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($issue->status === 'submitted') bg-primary
                                                    @elseif($issue->status === 'in_progress') bg-warning
                                                    @elseif($issue->status === 'resolved') bg-success
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($issue->priority === 'urgent') bg-danger
                                                    @elseif($issue->priority === 'high') bg-warning
                                                    @elseif($issue->priority === 'medium') bg-info
                                                    @else bg-success
                                                    @endif">
                                                    {{ ucfirst($issue->priority) }}
                                                </span>
                                            </td>
                                            <td>{{ $issue->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('issues.show', $issue) }}" 
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
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No Issues Yet</h5>
                            <p class="text-muted">No issues have been reported in this category.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection