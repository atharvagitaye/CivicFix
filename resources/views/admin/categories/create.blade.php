@extends('layouts.app')

@section('title', 'Create Category - CivicFix Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-plus-circle"></i> Create New Category
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
                    <form method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ request('name') ?? old('name') }}" 
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
                                      placeholder="Optional description to help users understand what types of issues belong in this category">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Optional: Provide additional context about what issues should be reported under this category.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Category
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

    <!-- Category Examples -->
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightbulb"></i> Category Examples
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Infrastructure Categories</h6>
                            <ul class="list-unstyled">
                                <li><strong>Roads & Transportation</strong><br>
                                    <small class="text-muted">Potholes, traffic lights, road signs, crosswalks</small></li>
                                <li><strong>Water & Drainage</strong><br>
                                    <small class="text-muted">Water main breaks, storm drains, flooding issues</small></li>
                                <li><strong>Electrical & Lighting</strong><br>
                                    <small class="text-muted">Street lights, power outages, electrical hazards</small></li>
                                <li><strong>Parks & Recreation</strong><br>
                                    <small class="text-muted">Playground equipment, park maintenance, trails</small></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Public Service Categories</h6>
                            <ul class="list-unstyled">
                                <li><strong>Waste Management</strong><br>
                                    <small class="text-muted">Garbage collection, recycling, illegal dumping</small></li>
                                <li><strong>Public Safety</strong><br>
                                    <small class="text-muted">Damaged sidewalks, missing signs, hazards</small></li>
                                <li><strong>Buildings & Facilities</strong><br>
                                    <small class="text-muted">Public buildings, restrooms, accessibility issues</small></li>
                                <li><strong>Environmental</strong><br>
                                    <small class="text-muted">Air quality, noise pollution, environmental hazards</small></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        <strong>Tip:</strong> After creating a category, you can add specific sub-categories to help users report more detailed issues.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on name field
    document.getElementById('name').focus();
    
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