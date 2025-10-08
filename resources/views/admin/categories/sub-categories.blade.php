@extends('layouts.app')

@section('title', 'Manage Sub-Categories: {{ $category->name }} - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-collection"></i> Sub-Categories: {{ $category->name }}</h2>
        <div class="btn-group">
            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-secondary">
                <i class="bi bi-eye"></i> View Category
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Add New Sub-Category -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add New Sub-Category</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.categories.sub-categories.store', $category) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Sub-category name"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Brief description of this sub-category">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle"></i> Add Sub-Category
                        </button>
                    </form>
                </div>
            </div>

            <!-- Category Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Category Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Issues:</strong></td>
                            <td><span class="badge bg-primary">{{ $category->issues_count ?? 0 }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Sub-Categories:</strong></td>
                            <td><span class="badge bg-info">{{ $category->subCategories->count() }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Existing Sub-Categories -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Existing Sub-Categories ({{ $category->subCategories->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($category->subCategories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Issues Count</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->subCategories as $subCategory)
                                        <tr>
                                            <td><strong>#{{ $subCategory->id }}</strong></td>
                                            <td>{{ $subCategory->name }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($subCategory->description ?? 'No description', 50) }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $subCategory->issues_count ?? 0 }} issues
                                                </span>
                                            </td>
                                            <td>{{ $subCategory->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-outline-warning btn-sm" 
                                                            onclick="editSubCategory({{ $subCategory->id }}, '{{ $subCategory->name }}', '{{ $subCategory->description }}')"
                                                            title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    @if($subCategory->issues_count == 0)
                                                        <form method="POST" 
                                                              action="{{ route('admin.categories.sub-categories.destroy', [$category, $subCategory]) }}" 
                                                              style="display: inline;"
                                                              onsubmit="return confirm('Are you sure you want to delete this sub-category?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-outline-danger btn-sm" 
                                                                    title="Delete">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-outline-secondary btn-sm" 
                                                                title="Cannot delete - has issues"
                                                                disabled>
                                                            <i class="bi bi-shield-x"></i>
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
                            <i class="bi bi-folder-x fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No Sub-Categories</h5>
                            <p class="text-muted">This category doesn't have any sub-categories yet. Use the form on the left to add the first one.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Sub-Category Modal -->
<div class="modal fade" id="editSubCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSubCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sub-Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name *</label>
                        <input type="text" 
                               class="form-control" 
                               id="edit_name" 
                               name="name" 
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="edit_description" 
                                  name="description" 
                                  rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Sub-Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function editSubCategory(id, name, description) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('editSubCategoryForm').action = `/admin/categories/{{ $category->id }}/sub-categories/${id}`;
    new bootstrap.Modal(document.getElementById('editSubCategoryModal')).show();
}
</script>
@endpush