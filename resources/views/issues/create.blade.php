@extends('layouts.app')

@section('title', 'Report New Issue - CivicFix')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Report New Infrastructure Issue
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('issues.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Issue Title *</label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       placeholder="Brief description of the issue"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority *</label>
                                <select class="form-select @error('priority') is-invalid @enderror" 
                                        id="priority" 
                                        name="priority" 
                                        required>
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>
                                        Low - Non-urgent, cosmetic issues
                                    </option>
                                    <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>
                                        Medium - Moderate impact on daily life
                                    </option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>
                                        High - Significant inconvenience or minor safety concern
                                    </option>
                                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>
                                        Urgent - Immediate safety hazard or major disruption
                                    </option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Detailed Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Provide detailed information about the issue..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" 
                                        name="category_id" 
                                        required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sub_category_id" class="form-label">Sub-Category *</label>
                                <select class="form-select @error('sub_category_id') is-invalid @enderror" 
                                        id="sub_category_id" 
                                        name="sub_category_id" 
                                        required>
                                    <option value="">Select Sub-Category</option>
                                </select>
                                @error('sub_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location_lat" class="form-label">Latitude *</label>
                                <input type="number" 
                                       step="any" 
                                       class="form-control @error('location_lat') is-invalid @enderror" 
                                       id="location_lat" 
                                       name="location_lat" 
                                       value="{{ old('location_lat') }}" 
                                       placeholder="e.g., 40.7128"
                                       required>
                                @error('location_lat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location_lng" class="form-label">Longitude *</label>
                                <input type="number" 
                                       step="any" 
                                       class="form-control @error('location_lng') is-invalid @enderror" 
                                       id="location_lng" 
                                       name="location_lng" 
                                       value="{{ old('location_lng') }}" 
                                       placeholder="e.g., -74.0060"
                                       required>
                                @error('location_lng')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-info" id="getLocationBtn">
                                <i class="bi bi-geo-alt"></i> Get My Current Location
                            </button>
                            <small class="text-muted ms-2">Click to automatically fill location coordinates</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('issues.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Submit Issue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle category change to load sub-categories
    const categorySelect = document.getElementById('category_id');
    const subCategorySelect = document.getElementById('sub_category_id');
    
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        subCategorySelect.innerHTML = '<option value="">Select Sub-Category</option>';
        
        if (categoryId) {
            fetch(`/api/categories/${categoryId}/sub-categories`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subCategory => {
                        const option = document.createElement('option');
                        option.value = subCategory.id;
                        option.textContent = subCategory.name;
                        subCategorySelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading sub-categories:', error);
                });
        }
    });
    
    // Handle get location button
    const getLocationBtn = document.getElementById('getLocationBtn');
    const latInput = document.getElementById('location_lat');
    const lngInput = document.getElementById('location_lng');
    
    getLocationBtn.addEventListener('click', function() {
        if (navigator.geolocation) {
            getLocationBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Getting Location...';
            getLocationBtn.disabled = true;
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latInput.value = position.coords.latitude.toFixed(6);
                    lngInput.value = position.coords.longitude.toFixed(6);
                    
                    getLocationBtn.innerHTML = '<i class="bi bi-check-circle"></i> Location Found!';
                    getLocationBtn.classList.remove('btn-info');
                    getLocationBtn.classList.add('btn-success');
                    
                    setTimeout(() => {
                        getLocationBtn.innerHTML = '<i class="bi bi-geo-alt"></i> Get My Current Location';
                        getLocationBtn.classList.remove('btn-success');
                        getLocationBtn.classList.add('btn-info');
                        getLocationBtn.disabled = false;
                    }, 2000);
                },
                function(error) {
                    alert('Unable to get your location. Please enter coordinates manually.');
                    getLocationBtn.innerHTML = '<i class="bi bi-geo-alt"></i> Get My Current Location';
                    getLocationBtn.disabled = false;
                }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });
});
</script>
@endpush