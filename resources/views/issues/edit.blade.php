@extends('layouts.app')

@section('title', 'Edit Issue - CivicFix')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="bi bi-pencil"></i> Edit Issue: {{ $issue->title }}
                </h1>
                <a href="{{ route('issues.show', $issue) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Issue
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Issue Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('issues.update', $issue) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                Issue Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $issue->title) }}" 
                                   placeholder="Brief description of the issue"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">
                                        Category <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" 
                                            name="category_id" 
                                            required>
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $issue->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subcategory_id" class="form-label">Sub-Category</label>
                                    <select class="form-select @error('subcategory_id') is-invalid @enderror" 
                                            id="subcategory_id" 
                                            name="subcategory_id">
                                        <option value="">Select a sub-category</option>
                                        @if($issue->category)
                                            @foreach($issue->category->subCategories as $subcategory)
                                                <option value="{{ $subcategory->id }}" 
                                                        {{ old('subcategory_id', $issue->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                                    {{ $subcategory->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('subcategory_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="5"
                                      placeholder="Provide detailed information about the issue, its location, and any relevant details"
                                      required>{{ old('description', $issue->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">
                                        Location <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('location') is-invalid @enderror" 
                                           id="location" 
                                           name="location" 
                                           value="{{ old('location', $issue->location) }}" 
                                           placeholder="Street address or landmark"
                                           required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">
                                        Priority <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('priority') is-invalid @enderror" 
                                            id="priority" 
                                            name="priority" 
                                            required>
                                        <option value="">Select priority level</option>
                                        <option value="low" {{ old('priority', $issue->priority) == 'low' ? 'selected' : '' }}>
                                            Low - Non-urgent, cosmetic issues
                                        </option>
                                        <option value="medium" {{ old('priority', $issue->priority) == 'medium' ? 'selected' : '' }}>
                                            Medium - Moderate impact on daily life
                                        </option>
                                        <option value="high" {{ old('priority', $issue->priority) == 'high' ? 'selected' : '' }}>
                                            High - Significant inconvenience or minor safety concern
                                        </option>
                                        <option value="urgent" {{ old('priority', $issue->priority) == 'urgent' ? 'selected' : '' }}>
                                            Urgent - Immediate safety hazard or major disruption
                                        </option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- GPS Coordinates (Hidden fields populated by geolocation) -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $issue->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $issue->longitude) }}">

                        <!-- Location Detection -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label">Current Location</label>
                                <button type="button" id="getLocationBtn" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-geo-alt"></i> Use My Location
                                </button>
                            </div>
                            <div id="locationStatus" class="text-muted small">
                                @if($issue->latitude && $issue->longitude)
                                    Current coordinates: {{ number_format($issue->latitude, 6) }}, {{ number_format($issue->longitude, 6) }}
                                @else
                                    Location not set. Click "Use My Location" to automatically detect your current position.
                                @endif
                            </div>
                        </div>

                        <!-- Current Media Display -->
                        @if($issue->media->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Current Attachments</label>
                                <div class="row">
                                    @foreach($issue->media as $media)
                                        <div class="col-md-3 mb-2">
                                            <div class="card">
                                                @if(str_starts_with($media->mime_type, 'image/'))
                                                    <img src="{{ Storage::url($media->file_path) }}" 
                                                         class="card-img-top" 
                                                         style="height: 120px; object-fit: cover;"
                                                         alt="Issue attachment">
                                                @else
                                                    <div class="card-body text-center py-3">
                                                        <i class="bi bi-file-earmark fs-2 text-muted"></i>
                                                        <div class="small text-muted">{{ $media->original_filename }}</div>
                                                    </div>
                                                @endif
                                                <div class="card-footer p-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">{{ number_format($media->file_size / 1024, 1) }} KB</small>
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm remove-media" 
                                                                data-media-id="{{ $media->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Add New Media -->
                        <div class="mb-3">
                            <label for="media" class="form-label">Add New Photos or Files</label>
                            <input type="file" 
                                   class="form-control @error('media.*') is-invalid @enderror" 
                                   id="media" 
                                   name="media[]" 
                                   multiple 
                                   accept="image/*,.pdf,.doc,.docx">
                            @error('media.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                You can upload multiple files (images, PDFs, Word documents). Maximum 5MB per file.
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Issue
                            </button>
                            <a href="{{ route('issues.show', $issue) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category change handler for subcategories
    document.getElementById('category_id').addEventListener('change', function() {
        const categoryId = this.value;
        const subcategorySelect = document.getElementById('subcategory_id');
        
        // Clear current options
        subcategorySelect.innerHTML = '<option value="">Loading...</option>';
        
        if (categoryId) {
            fetch(`/api/categories/${categoryId}/subcategories`)
                .then(response => response.json())
                .then(data => {
                    subcategorySelect.innerHTML = '<option value="">Select a sub-category</option>';
                    data.forEach(subcategory => {
                        subcategorySelect.innerHTML += `<option value="${subcategory.id}">${subcategory.name}</option>`;
                    });
                })
                .catch(error => {
                    subcategorySelect.innerHTML = '<option value="">Error loading sub-categories</option>';
                });
        } else {
            subcategorySelect.innerHTML = '<option value="">Select a sub-category</option>';
        }
    });

    // Geolocation functionality
    document.getElementById('getLocationBtn').addEventListener('click', function() {
        const button = this;
        const status = document.getElementById('locationStatus');
        
        if (!navigator.geolocation) {
            status.textContent = 'Geolocation is not supported by this browser.';
            status.className = 'text-danger small';
            return;
        }

        button.disabled = true;
        button.innerHTML = '<i class="bi bi-geo-alt"></i> Getting Location...';
        status.textContent = 'Detecting your location...';
        status.className = 'text-info small';

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                
                status.textContent = `Location detected: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                status.className = 'text-success small';
                
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-geo-alt"></i> Location Updated';
                
                setTimeout(() => {
                    button.innerHTML = '<i class="bi bi-geo-alt"></i> Use My Location';
                }, 2000);
            },
            function(error) {
                let errorMessage = 'Unable to get your location.';
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Location access denied by user.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Location information is unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Location request timed out.';
                        break;
                }
                
                status.textContent = errorMessage;
                status.className = 'text-danger small';
                
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-geo-alt"></i> Try Again';
            }
        );
    });

    // Media removal functionality
    document.querySelectorAll('.remove-media').forEach(button => {
        button.addEventListener('click', function() {
            const mediaId = this.dataset.mediaId;
            
            if (confirm('Are you sure you want to remove this file?')) {
                fetch(`/issues/media/${mediaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.col-md-3').remove();
                    } else {
                        alert('Failed to remove file. Please try again.');
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                });
            }
        });
    });

    // File size validation
    document.getElementById('media').addEventListener('change', function() {
        const files = this.files;
        const maxSize = 5 * 1024 * 1024; // 5MB
        let hasError = false;
        
        for (let i = 0; i < files.length; i++) {
            if (files[i].size > maxSize) {
                alert(`File "${files[i].name}" is too large. Maximum size is 5MB.`);
                hasError = true;
            }
        }
        
        if (hasError) {
            this.value = '';
        }
    });
});
</script>
@endsection