@extends('layouts.app')

@section('title')
    Assign Issue #{{ $issue->id }} - Admin Panel
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-person-plus"></i> Assign Issue #{{ $issue->id }}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Issue Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{ $issue->title }}</h5>
                            <p class="text-muted">{{ \Illuminate\Support\Str::limit($issue->description, 100) }}</p>
                            
                            <div class="mb-2">
                                <strong>Reported by:</strong> {{ $issue->user->name }}<br>
                                <strong>Category:</strong> {{ $issue->category->name }}
                                @if($issue->subcategory) > {{ $issue->subcategory->name }} @endif<br>
                                <strong>Priority:</strong> 
                                <span class="badge 
                                    @if($issue->priority === 'urgent') bg-danger
                                    @elseif($issue->priority === 'high') bg-warning
                                    @elseif($issue->priority === 'medium') bg-info
                                    @else bg-success
                                    @endif">
                                    {{ ucfirst($issue->priority) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <strong>Location:</strong><br>
                                {{ $issue->location ?? 'Not specified' }}<br>
                                @if($issue->latitude && $issue->longitude)
                                    <small class="text-muted">
                                        Coordinates: {{ $issue->latitude }}, {{ $issue->longitude }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Form -->
                    <form method="POST" action="{{ route('admin.issues.assign.store', $issue) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="staff_id" class="form-label">Assign to Staff Member *</label>
                            <select class="form-select @error('staff_id') is-invalid @enderror" 
                                    id="staff_id" 
                                    name="staff_id" 
                                    required>
                                <option value="">Select Staff Member</option>
                                @foreach($staffMembers as $staff)
                                    <option value="{{ $staff->id }}" {{ old('staff_id') == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->user->name }} - {{ $staff->user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('staff_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="assignment_notes" class="form-label">Assignment Notes</label>
                            <textarea class="form-control @error('assignment_notes') is-invalid @enderror" 
                                      id="assignment_notes" 
                                      name="assignment_notes" 
                                      rows="3" 
                                      placeholder="Optional notes for the assigned staff member">{{ old('assignment_notes') }}</textarea>
                            @error('assignment_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.issues.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Issues
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Assign Issue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection