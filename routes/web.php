<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Staff\StaffController as StaffStaffController;
use App\Http\Controllers\HomeController;

// Public routes
Route::get('/welcome', [HomeController::class, 'showWelcome']);
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password reset routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    
    // General dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Issues routes (accessible by all authenticated users with different permissions based on role)
    Route::resource('issues', IssueController::class);
    
    // Media upload routes
    Route::post('/issues/{issue}/media', [MediaController::class, 'store'])->name('issues.media.store');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    
    // Admin-only routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/issues', [AdminController::class, 'issues'])->name('issues.index');
        
        // Issue assignment routes
        Route::get('/issues/{issue}/assign', [AdminController::class, 'assignIssue'])->name('issues.assign');
        Route::post('/issues/{issue}/assign', [AdminController::class, 'storeAssignment'])->name('issues.assign.store');
    // Status update (AJAX) - allow admins to update issue status
    Route::put('/issues/{issue}/status', [AdminController::class, 'updateStatus'])->name('issues.status.update');
        
        // User promotion routes
        Route::post('/users/promote-admin', [AdminController::class, 'promoteToAdmin'])->name('users.promote.admin');
        Route::post('/users/promote-staff', [AdminController::class, 'promoteToStaff'])->name('users.promote.staff');
        
        // Category management routes
        Route::resource('categories', CategoryController::class);
        Route::get('/categories/{category}/sub-categories', [CategoryController::class, 'subCategories'])->name('categories.sub-categories');
        Route::post('/categories/{category}/sub-categories', [CategoryController::class, 'storeSubCategory'])->name('categories.sub-categories.store');
        Route::put('/categories/{category}/sub-categories/{subCategory}', [CategoryController::class, 'updateSubCategory'])->name('categories.sub-categories.update');
        Route::delete('/categories/{category}/sub-categories/{subCategory}', [CategoryController::class, 'destroySubCategory'])->name('categories.sub-categories.destroy');
        
        // Staff management routes
        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{staff}', [StaffController::class, 'show'])->name('staff.show');
        Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    });
    
    // Staff-only routes
    Route::middleware(['staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffStaffController::class, 'dashboard'])->name('dashboard');
        Route::get('/issues', [StaffStaffController::class, 'assignedIssues'])->name('issues');
        Route::get('/issues/{issue}', [StaffStaffController::class, 'showIssue'])->name('issues.show');
        Route::post('/issues/{issue}/update', [StaffStaffController::class, 'updateIssue'])->name('issues.update');
        Route::post('/issues/{issue}/complete', [StaffStaffController::class, 'completeIssue'])->name('issues.complete');
    });
    
    // API routes for dynamic data (accessible by authenticated users)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/categories/{category}/sub-categories', function (\App\Models\Category $category) {
            return response()->json($category->subCategories);
        })->name('categories.sub-categories');
    });
});
