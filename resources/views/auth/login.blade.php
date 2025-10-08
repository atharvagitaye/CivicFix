@extends('layouts.app')

@section('title', 'Login - CivicFix')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h3><i class="bi bi-tools"></i> CivicFix</h3>
                    <p class="mb-0">Infrastructure Issue Reporter</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            Forgot your password?
                        </a>
                    </div>

                    <hr>

                    <div class="text-center">
                        <p class="mb-0">Don't have an account?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-person-plus"></i> Register
                        </a>
                    </div>
                </div>
            </div>

            <!-- Demo Credentials -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title text-center">Demo Credentials</h6>
                    <small class="text-muted">
                        <strong>Admin:</strong> admin@civicfix.com / admin123<br>
                        <strong>Staff:</strong> staff@civicfix.com / staff123<br>
                        <strong>User:</strong> user@civicfix.com / user123
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection