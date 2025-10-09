<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CivicFix - Infrastructure Issue Reporter</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .stats-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        .stat-card {
            text-align: center;
            padding: 2rem;
            border-radius: 10px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .section-padding {
            padding: 80px 0;
        }
        .feature-card {
            text-align: center;
            padding: 2rem;
            height: 100%;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-tools text-primary"></i> CivicFix
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Statistics</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary-custom text-white ms-2" href="{{ route('register') }}">Get Started</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Report Infrastructure Issues Easily</h1>
                    <p class="lead mb-4">CivicFix helps communities report and track infrastructure problems like potholes, broken streetlights, water leaks, and more. Join us in making our cities better.</p>
                    <div class="d-flex gap-3">
                        @auth
                            <a href="{{ route('issues.create') }}" class="btn btn-light btn-lg">
                                <i class="bi bi-plus-circle"></i> Report an Issue
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                                <i class="bi bi-person-plus"></i> Join CivicFix
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-geo-alt-fill" style="font-size: 15rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section id="stats" class="stats-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Community Impact</h2>
                    <p class="text-muted">See how CivicFix is making a difference in our community</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <i class="bi bi-exclamation-triangle feature-icon"></i>
                        <div class="stat-number">{{ number_format($stats['total_issues']) }}</div>
                        <h5>Total Issues Reported</h5>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <i class="bi bi-check-circle feature-icon text-success"></i>
                        <div class="stat-number text-success">{{ number_format($stats['resolved_issues']) }}</div>
                        <h5>Issues Resolved</h5>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <i class="bi bi-clock feature-icon text-warning"></i>
                        <div class="stat-number text-warning">{{ number_format($stats['in_progress_issues']) }}</div>
                        <h5>In Progress</h5>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <i class="bi bi-tags feature-icon text-info"></i>
                        <div class="stat-number text-info">{{ number_format($stats['categories']) }}</div>
                        <h5>Issue Categories</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">How CivicFix Works</h2>
                    <p class="text-muted">Simple, effective, and transparent infrastructure issue reporting</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-camera feature-icon"></i>
                        <h4>Report Issues</h4>
                        <p>Take a photo, add location details, and describe the infrastructure problem in your community.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-people feature-icon"></i>
                        <h4>Staff Assignment</h4>
                        <p>Issues are automatically routed to the appropriate municipal staff for quick resolution.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-graph-up feature-icon"></i>
                        <h4>Track Progress</h4>
                        <p>Monitor the status of your reported issues and receive updates throughout the resolution process.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-geo-alt feature-icon"></i>
                        <h4>Location Mapping</h4>
                        <p>GPS-enabled reporting ensures precise location tracking for faster response times.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-shield-check feature-icon"></i>
                        <h4>Priority System</h4>
                        <p>Issues are categorized by priority to ensure urgent problems receive immediate attention.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-chat-dots feature-icon"></i>
                        <h4>Community Updates</h4>
                        <p>Stay informed with real-time updates and communication from municipal staff.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section-padding bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">About CivicFix</h2>
                    <p class="lead">CivicFix is a modern platform that bridges the gap between citizens and municipal services, making it easier than ever to report and resolve infrastructure issues.</p>
                    <p>Our mission is to create smarter, more responsive communities by empowering citizens to directly communicate infrastructure needs to local government and service providers.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Easy-to-use mobile and web interface</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Real-time issue tracking and updates</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Automated staff assignment and routing</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Comprehensive reporting and analytics</li>
                    </ul>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-4 bg-white rounded shadow-sm">
                                <i class="bi bi-lightning-charge feature-icon text-warning"></i>
                                <h6>Fast Response</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 bg-white rounded shadow-sm">
                                <i class="bi bi-phone feature-icon text-primary"></i>
                                <h6>Mobile Ready</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 bg-white rounded shadow-sm">
                                <i class="bi bi-eye feature-icon text-info"></i>
                                <h6>Transparent</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 bg-white rounded shadow-sm">
                                <i class="bi bi-heart feature-icon text-danger"></i>
                                <h6>Community</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section-padding" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container text-center text-white">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-4">Ready to Make a Difference?</h2>
                    <p class="lead mb-4">Join thousands of citizens who are already using CivicFix to improve their communities. Report your first issue today!</p>
                    @auth
                        <a href="{{ route('issues.create') }}" class="btn btn-light btn-lg me-3">
                            <i class="bi bi-plus-circle"></i> Report an Issue
                        </a>
                        <a href="{{ route('issues.index') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-list"></i> View Issues
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                            <i class="bi bi-person-plus"></i> Sign Up Free
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="text-white"><i class="bi bi-tools"></i> CivicFix</h5>
                    <p class="text-light">Making communities better, one issue at a time. Report infrastructure problems and track their resolution with ease.</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="text-white">Platform</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-light text-decoration-none">Features</a></li>
                        <li><a href="#about" class="text-light text-decoration-none">About</a></li>
                        <li><a href="#stats" class="text-light text-decoration-none">Statistics</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="text-white">Account</h6>
                    <ul class="list-unstyled">
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-light text-decoration-none">Dashboard</a></li>
                            <li><a href="{{ route('issues.index') }}" class="text-light text-decoration-none">My Issues</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-light text-decoration-none">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-light text-decoration-none">Register</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="text-white">Contact Info</h6>
                    <p class="text-light mb-2"><i class="bi bi-envelope"></i> support@civicfix.com</p>
                    <p class="text-light mb-2"><i class="bi bi-phone"></i> +1 (555) 123-4567</p>
                    <p class="text-light"><i class="bi bi-geo-alt"></i> 123 Municipal St, City, State 12345</p>
                </div>
            </div>
            <hr class="my-4 border-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light mb-0">&copy; {{ date('Y') }} CivicFix. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-light">Built with ❤️ for better communities</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth scrolling for anchor links -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>