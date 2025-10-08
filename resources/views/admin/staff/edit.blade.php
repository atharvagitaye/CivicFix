@extends('layouts.app')

@section('title', 'Edit Staff: {{ $staff->user->name }} - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> Edit Staff Member: {{ $staff->user->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.staff.update', $staff) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">User Account</label>
                                <div class="bg-light p-3 rounded">
                                    <strong>{{ $staff->user->name }}</strong><br>
                                    <small class="text-muted">{{ $staff->user->email }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Staff ID</label>
                                <div class="bg-light p-3 rounded">
                                    <strong>#{{ $staff->id }}</strong><br>
                                    <small class="text-muted">Joined {{ $staff->created_at->format('F d, Y') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $staff->phone) }}" 
                                       placeholder="e.g., +1234567890">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" 
                                       class="form-control @error('department') is-invalid @enderror" 
                                       id="department" 
                                       name="department" 
                                       value="{{ old('department', $staff->department) }}" 
                                       placeholder="e.g., Public Works">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">Employee ID</label>
                                <input type="text" 
                                       class="form-control @error('employee_id') is-invalid @enderror" 
                                       id="employee_id" 
                                       name="employee_id" 
                                       value="{{ old('employee_id', $staff->employee_id) }}" 
                                       placeholder="e.g., EMP001">
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Assigned Issues</label>
                                <div class="bg-light p-3 rounded">
                                    <strong>{{ $staff->assignments_count ?? 0 }}</strong> active assignments
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Full address">{{ old('address', $staff->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio / Notes</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" 
                                      name="bio" 
                                      rows="3" 
                                      placeholder="Additional information about the staff member">{{ old('bio', $staff->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Staff
                            </a>
                            <div>
                                <a href="{{ route('admin.staff.show', $staff) }}" class="btn btn-outline-primary me-2">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Update Staff Member
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection