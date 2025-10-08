@extends('layouts.app')

@section('title', 'Category Management - CivicFix Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-tags"></i> Category Management
                </h1>
                <div>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Category
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Categories</h5>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Sub-Categories</th>
                                        <th>Total Issues</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>
                                                <strong>{{ $category->name }}</strong>
                                                @if($category->description)
                                                    <br><small class="text-muted">{{ $category->description }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($category->subCategories->count() > 0)
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($category->subCategories as $subCategory)
                                                            <span class="badge bg-secondary">{{ $subCategory->name }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">No sub-categories</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $category->issues->count() }}
                                                </span>
                                            </td>
                                            <td>{{ $category->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <a href="{{ route('admin.categories.sub-categories', $category) }}" 
                                                       class="btn btn-outline-secondary btn-sm">
                                                        <i class="bi bi-collection"></i> Sub-Categories
                                                    </a>
                                                    @if($category->issues->count() == 0)
                                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    onclick="return confirm('Are you sure you want to delete this category?')">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-outline-danger btn-sm" disabled title="Cannot delete category with existing issues">
                                                            <i class="bi bi-trash"></i> Delete
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
                            <i class="bi bi-tags fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No categories found</h5>
                            <p class="text-muted">Create your first category to organize infrastructure issues.</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add First Category
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart"></i> Category Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card text-center">
                                <h4 class="text-primary">{{ $categories->count() }}</h4>
                                <p class="text-muted mb-0">Total Categories</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center">
                                <h4 class="text-info">{{ $categories->sum(function($cat) { return $cat->subCategories->count(); }) }}</h4>
                                <p class="text-muted mb-0">Total Sub-Categories</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center">
                                <h4 class="text-success">{{ $categories->sum(function($cat) { return $cat->issues->count(); }) }}</h4>
                                <p class="text-muted mb-0">Total Issues</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center">
                                <h4 class="text-warning">{{ $categories->where('subCategories', '==', collect())->count() }}</h4>
                                <p class="text-muted mb-0">Categories Without Sub-Categories</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
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
                        <div class="col-md-6">
                            <h6>Common Infrastructure Categories</h6>
                            <p class="text-muted small">Click to quickly create standard categories:</p>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-outline-primary btn-sm quick-category" data-name="Roads & Transportation">Roads & Transportation</button>
                                <button class="btn btn-outline-primary btn-sm quick-category" data-name="Water & Drainage">Water & Drainage</button>
                                <button class="btn btn-outline-primary btn-sm quick-category" data-name="Electrical & Lighting">Electrical & Lighting</button>
                                <button class="btn btn-outline-primary btn-sm quick-category" data-name="Parks & Recreation">Parks & Recreation</button>
                                <button class="btn btn-outline-primary btn-sm quick-category" data-name="Waste Management">Waste Management</button>
                                <button class="btn btn-outline-primary btn-sm quick-category" data-name="Public Safety">Public Safety</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Category Guidelines</h6>
                            <ul class="small text-muted">
                                <li>Use clear, descriptive names for categories</li>
                                <li>Group related issues under broader categories</li>
                                <li>Create sub-categories for specific issue types</li>
                                <li>Categories with existing issues cannot be deleted</li>
                                <li>Consider the end-user perspective when naming categories</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    padding: 20px;
    border-radius: 8px;
    background: #f8f9fa;
    margin-bottom: 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick category creation
    document.querySelectorAll('.quick-category').forEach(button => {
        button.addEventListener('click', function() {
            const categoryName = this.dataset.name;
            if (confirm(`Create category "${categoryName}"?`)) {
                // Redirect to create form with pre-filled name
                window.location.href = `{{ route('admin.categories.create') }}?name=${encodeURIComponent(categoryName)}`;
            }
        });
    });
});
</script>
@endsection