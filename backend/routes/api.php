Route::middleware('auth:sanctum')->get('/analytics/issues-by-status', [App\Http\Controllers\AnalyticsController::class, 'issuesByStatus']);
Route::middleware('auth:sanctum')->get('/analytics/issues-by-category', [App\Http\Controllers\AnalyticsController::class, 'issuesByCategory']);
Route::middleware('auth:sanctum')->get('/analytics/issues-by-date', [App\Http\Controllers\AnalyticsController::class, 'issuesByDate']);
Route::post('/password/forgot', [App\Http\Controllers\PasswordResetController::class, 'requestReset']);
Route::post('/password/reset', [App\Http\Controllers\PasswordResetController::class, 'resetPassword']);
Route::middleware(['auth:sanctum', 'staff_or_admin'])->apiResource('staffs', App\Http\Controllers\StaffController::class);
Route::middleware(['auth:sanctum', 'staff_or_admin'])->apiResource('admins', App\Http\Controllers\AdminController::class);
Route::middleware('auth:sanctum')->get('/user/profile', [App\Http\Controllers\UserController::class, 'profile']);
Route::middleware('auth:sanctum')->put('/user/profile', [App\Http\Controllers\UserController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->get('/user/issues', [App\Http\Controllers\UserController::class, 'myIssues']);
Route::middleware(['auth:sanctum', 'staff_or_admin'])->apiResource('categories', CategoryController::class);
Route::middleware(['auth:sanctum', 'staff_or_admin'])->apiResource('sub-categories', SubCategoryController::class);
Route::middleware(['auth:sanctum', 'staff_or_admin'])->patch('/issues/{id}/status', [IssueController::class, 'updateStatus']);
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IssueController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/issues', [IssueController::class, 'store']);
Route::middleware('auth:sanctum')->get('/issues', [IssueController::class, 'index']);



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');