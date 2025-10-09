@extends('layouts.app')

@section('title', 'Issue #{{ $issue->id }} - CivicFix')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Issue Details -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Issue #{{ $issue->id }}: {{ $issue->title }}</h4>
                        <div class="d-flex gap-2">
                            <span class="badge 
                                @if($issue->status === 'submitted') bg-primary
                                @elseif($issue->status === 'in_progress') bg-warning
                                @elseif($issue->status === 'resolved') bg-success
                                @elseif($issue->status === 'closed') bg-secondary
                                @else bg-info
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                            </span>
                            <span class="badge 
                                @if($issue->priority === 'urgent') bg-danger
                                @elseif($issue->priority === 'high') bg-warning
                                @elseif($issue->priority === 'medium') bg-info
                                @else bg-success
                                @endif">
                                {{ ucfirst($issue->priority) }} Priority
                            </span>
                        </div>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('issues.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Issues
                        </a>
                        @if(Auth::user()->isAdmin() || (!Auth::user()->isStaff() && $issue->user_id === Auth::id()))
                            <a href="{{ route('issues.edit', $issue) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Reported by:</strong> {{ $issue->user->name }}<br>
                            <strong>Category:</strong> {{ $issue->category->name }} 
                            @if($issue->subcategory) > {{ $issue->subcategory->name }} @endif<br>
                            <strong>Created:</strong> {{ $issue->created_at->format('F d, Y \a\t g:i A') }}<br>
                            @if($issue->updated_at != $issue->created_at)
                                <strong>Last Updated:</strong> {{ $issue->updated_at->format('F d, Y \a\t g:i A') }}
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Assigned to:</strong> 
                            @if($issue->assignment && $issue->assignment->staff)
                                {{ $issue->assignment->staff->user->name }}
                            @else
                                <span class="text-muted">Unassigned</span>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.issues.assign', $issue) }}" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="bi bi-person-plus"></i> Assign
                                    </a>
                                @endif
                            @endif
                            <br>
                            <strong>Location:</strong>
                            @if($issue->latitude && $issue->longitude)
                                {{ $issue->latitude }}, {{ $issue->longitude }}<br>
                            @else
                                <span class="text-muted">Location not specified</span><br>
                            @endif
                            <strong>Priority:</strong> {{ $issue->priority }}
                        </div>
                    </div>

                    <h5>Description</h5>
                    <div class="bg-light p-3 rounded mb-4">
                        {{ $issue->description }}
                    </div>

                    <!-- Location Map -->
                    <h5>Location</h5>
                    <div class="bg-light p-3 rounded mb-4 text-center">
                        <i class="bi bi-geo-alt fs-1 text-muted"></i>
                        @if($issue->latitude && $issue->longitude)
                            <p class="mb-0">Latitude: {{ $issue->latitude }}, Longitude: {{ $issue->longitude }}</p>
                            <a href="https://www.google.com/maps?q={{ $issue->latitude }},{{ $issue->longitude }}" 
                               target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="bi bi-map"></i> View on Google Maps
                            </a>
                        @else
                            <p class="mb-0 text-muted">Location coordinates are not available.</p>
                        @endif
                    </div>

                    <!-- Media Files -->
                    @if($issue->media->count() > 0)
                        <h5>Attachments</h5>
                        <div class="row mb-4">
                            @foreach($issue->media as $media)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        @if($media->isPhoto())
                                            <img src="{{ $media->media_url }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                <i class="bi bi-file-earmark fs-1 text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body p-2">
                                            <small class="text-muted">{{ ucfirst($media->media_type) }}</small>
                                            <div class="btn-group w-100 mt-1">
                                                <a href="{{ $media->media_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-download"></i> View
                                                </a>
                                                @if(Auth::user()->isAdmin() || $issue->user_id === Auth::id())
                                                    <form method="POST" action="{{ route('media.destroy', $media) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                onclick="return confirm('Are you sure you want to delete this file?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Add Media -->
                    @if(Auth::user()->isAdmin() || $issue->user_id === Auth::id())
                        <div class="border-top pt-3">
                            <h6>Add Attachment</h6>
                            <form method="POST" action="{{ route('issues.media.store', $issue) }}" enctype="multipart/form-data" class="row g-3">`
                                @csrf
                                <div class="col-md-6">
                                    <input type="file" class="form-control" name="media" accept="image/*,.pdf,.doc,.docx">
                                </div>
                                <div class="col-md-3">
                                    <select name="media_type" class="form-select">
                                        <option value="photo">Photo</option>
                                        <option value="document">Document</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-upload"></i> Upload
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Updates & Actions -->
        <div class="col-lg-4">
            <!-- Staff Actions -->
            @if(Auth::user()->isStaff() && $issue->assignment && $issue->assignment->staff_id === Auth::user()->staff->id)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-tools"></i> Staff Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('staff.issues.update', $issue) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    @foreach($statusOptions ?? [] as $value => $label)
                                        <option value="{{ $value }}" {{ $issue->status == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="update_description" class="form-label">Progress Notes</label>
                                <textarea class="form-control" id="update_description" name="update_description" rows="3" 
                                          placeholder="Describe what work has been done or planned..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle"></i> Update Issue
                            </button>
                        </form>
                        
                        @if($issue->status !== 'closed')
                            <hr>
                            <form method="POST" action="{{ route('staff.issues.complete', $issue) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" 
                                        onclick="return confirm('Are you sure you want to mark this issue as completed?')">
                                    <i class="bi bi-check-circle"></i> Mark as Completed
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Issue Updates -->
            <!-- <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Update History
                    </h5>
                </div>
                <div class="card-body">
                    @if($issue->updates->count() > 0)
                        <div class="timeline">
                            @foreach($issue->updates->sortByDesc('created_at') as $update)
                                <div class="timeline-item mb-3 pb-3 @if(!$loop->last) border-bottom @endif">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $update->staff->user->name }}</strong>
                                            <small class="text-muted d-block">{{ $update->created_at->format('M d, Y \a\t g:i A') }}</small>
                                        </div>
                                        @php
                                            // Normalize status which might be a plain string or a JSON-encoded object
                                            $statusRaw = $update->status;
                                            $statusLabel = null;
                                            $statusKey = null;

                                            if (!empty($statusRaw) && is_string($statusRaw)) {
                                                $maybeStatus = json_decode($statusRaw, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($maybeStatus)) {
                                                    // prefer 'name' if present
                                                    if (isset($maybeStatus['name'])) {
                                                        $statusLabel = $maybeStatus['name'];
                                                        $statusKey = strtolower(str_replace(' ', '_', $statusLabel));
                                                    } elseif (isset($maybeStatus['status'])) {
                                                        $statusLabel = $maybeStatus['status'];
                                                        $statusKey = strtolower(str_replace(' ', '_', $statusLabel));
                                                    }
                                                }
                                            }

                                            if (!$statusLabel) {
                                                // fallback to existing plain string handling
                                                $statusLabel = $statusRaw;
                                                $statusKey = is_string($statusRaw) ? $statusRaw : null;
                                            }

                                            // Ensure we have a display label
                                            $displayLabel = $statusLabel ? ucwords(str_replace('_', ' ', $statusLabel)) : 'Unknown';
                                        @endphp

                                        <span class="badge 
                                            @if($statusKey === 'submitted') bg-info
                                            @elseif($statusKey === 'in_progress') bg-warning
                                            @elseif($statusKey === 'resolved') bg-success
                                            @elseif($statusKey === 'closed') bg-secondary
                                            @else bg-secondary
                                            @endif">
                                            {{ $displayLabel }}
                                        </span>
                                    </div>
                                    @php
                                        // Robust JSON decoding: handle double-encoded JSON and various shapes
                                        $decoded = null;
                                        $raw = $update->update_description;

                                        if (!empty($raw) && is_string($raw)) {
                                            $maybe = json_decode($raw, true);
                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                $decoded = $maybe;
                                                // handle double-encoded values (strings that are JSON themselves)
                                                if (is_string($decoded)) {
                                                    $inner = json_decode($decoded, true);
                                                    if (json_last_error() === JSON_ERROR_NONE) {
                                                        $decoded = $inner;
                                                    }
                                                }
                                            }
                                        }

                                        // Helper to detect associative arrays
                                        $isAssoc = function($arr) {
                                            if (!is_array($arr)) return false;
                                            return array_keys($arr) !== range(0, count($arr) - 1);
                                        };
                                    @endphp

                                    @if($decoded)
                                        <div class="mt-2 mb-0">
                                            @if(is_array($decoded) && isset($decoded['notes']))
                                                <p>{{ $decoded['notes'] }}</p>
                                            @elseif(is_array($decoded) && isset($decoded['name']))
                                                <p><strong>Status changed to:</strong> {{ $decoded['name'] }}</p>
                                                @if(!empty($decoded['description']))
                                                    <p class="text-muted small">{{ $decoded['description'] }}</p>
                                                @endif
                                            @elseif(is_array($decoded) && $isAssoc($decoded))
                                                <div class="alert alert-info small mb-0">
                                                    @foreach($decoded as $key => $value)
                                                        @if(is_scalar($value))
                                                            <strong>{{ ucwords(str_replace(['_', "\n"], [' ', ' '], $key)) }}:</strong> {{ $value }}<br>
                                                        @elseif(is_array($value))
                                                            <strong>{{ ucwords(str_replace(['_', "\n"], [' ', ' '], $key)) }}:</strong>
                                                            <pre class="mb-0 small">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @elseif(is_array($decoded))
                                                {{-- indexed array - list items --}}
                                                <ul class="small mb-0">
                                                    @foreach($decoded as $item)
                                                        <li>
                                                            @if(is_scalar($item))
                                                                {{ $item }}
                                                            @else
                                                                <pre class="mb-0 small">{{ json_encode($item, JSON_PRETTY_PRINT) }}</pre>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                {{-- fallback: pretty-print whatever we decoded --}}
                                                <pre class="mb-0 small">{{ json_encode($decoded, JSON_PRETTY_PRINT) }}</pre>
                                            @endif
                                        </div>
                                    @else
                                        <p class="mt-2 mb-0">{{ $update->update_description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-clock fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No updates yet</p>
                        </div>
                    @endif
                </div>
            </div> -->
        </div>
    </div>
</div>
@endsection