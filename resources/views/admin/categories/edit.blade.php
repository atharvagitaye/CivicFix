@extends('layouts.app')

@section('title', 'Edit Category - CivicFix Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-pencil"></i> Edit Category: {{ $category->name }}
                </h1>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Categories
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Category Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}" 
                                   placeholder="e.g., Roads & Transportation"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Choose a clear, descriptive name that users will easily understand.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Optional description to help users understand what types of issues belong in this category">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Optional: Provide additional context about what issues should be reported under this category.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Category
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Usage Statistics -->
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart"></i> Category Usage
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $category->subCategories->count() }}</h4>
                                <p class="text-muted mb-0">Sub-Categories</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-info">{{ $category->issues->count() }}</h4>
                                <p class="text-muted mb-0">Total Issues</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-success">{{ $category->created_at->format('M d, Y') }}</h4>
                                <p class="text-muted mb-0">Created Date</p>
                            </div>
                        </div>
                    </div>

                    @if($category->subCategories->count() > 0)
                        <hr>
                        <div class="mt-3">
                            <h6>Sub-Categories:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($category->subCategories as $subCategory)
                                    <span class="badge bg-secondary">
                                        {{ $subCategory->name }}
                                        <span class="badge bg-light text-dark ms-1">{{ $subCategory->issues->count() }}</span>
                                    </span>
                                @endforeach
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('admin.categories.sub-categories', $category) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-collection"></i> Manage Sub-Categories
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($category->issues->count() > 0)
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Note:</strong> This category has {{ $category->issues->count() }} associated issues. 
                            Changes to the category name will be reflected in all existing issues.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    @if($category->issues->count() == 0)
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Danger Zone
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Delete this category permanently. This action cannot be undone.
                        </p>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                <i class="bi bi-trash"></i> Delete Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for description
    const descriptionField = document.getElementById('description');
    const maxLength = 500;
    
    // Create character counter
    const counterDiv = document.createElement('div');
    counterDiv.className = 'form-text text-muted';
    counterDiv.innerHTML = `<span id="char-count">0</span>/${maxLength} characters`;
    descriptionField.parentNode.appendChild(counterDiv);
    
    const charCount = document.getElementById('char-count');
    
    descriptionField.addEventListener('input', function() {
        const currentLength = this.value.length;
        charCount.textContent = currentLength;
        
        if (currentLength > maxLength * 0.9) {
            charCount.parentNode.className = 'form-text text-warning';
        } else {
            charCount.parentNode.className = 'form-text text-muted';
        }
    });
    
    // Trigger initial count
    descriptionField.dispatchEvent(new Event('input'));
});
</script>
@endsection