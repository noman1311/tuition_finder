<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OfferController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('search');

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Profile completion routes
Route::middleware('auth')->group(function () {
    Route::get('/complete-student-profile', [LoginController::class, 'showStudentProfileForm'])->name('student.profile');
    Route::post('/complete-student-profile', [LoginController::class, 'completeStudentProfile'])->name('student.profile.complete');
    Route::get('/complete-teacher-profile', [LoginController::class, 'showTeacherProfileForm'])->name('teacher.profile');
    Route::post('/complete-teacher-profile', [LoginController::class, 'completeTeacherProfile'])->name('teacher.profile.complete');

    // Student profile edit/update
    Route::get('/student/profile/edit', [LoginController::class, 'editStudentProfile'])->name('student.profile.edit');
    Route::post('/student/profile/update', [LoginController::class, 'updateStudentProfile'])->name('student.profile.update');
    
    // Teacher profile edit/update
    Route::get('/teacher/profile/edit', [LoginController::class, 'editTeacherProfile'])->name('teacher.profile.edit');
    Route::post('/teacher/profile/update', [LoginController::class, 'updateTeacherProfile'])->name('teacher.profile.update');
    
    // Role switching for teachers
    Route::get('/switch-to-student', [LoginController::class, 'switchToStudent'])->name('switch.to.student');
    Route::get('/switch-to-teacher', [LoginController::class, 'switchToTeacher'])->name('switch.to.teacher');
    
    // Teacher dashboard and jobs
    Route::get('/teacher/dashboard', [App\Http\Controllers\TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('/teacher/jobs/my-jobs', [App\Http\Controllers\TeacherController::class, 'myJobs'])->name('teacher.jobs.my');
    Route::get('/teacher/jobs/online', [App\Http\Controllers\TeacherController::class, 'onlineJobs'])->name('teacher.jobs.online');
    Route::get('/teacher/jobs/all', [App\Http\Controllers\TeacherController::class, 'allJobs'])->name('teacher.jobs.all');
    Route::post('/teacher/apply/{offerId}', [App\Http\Controllers\TeacherController::class, 'applyJob'])->name('teacher.apply');
});

// Tuition offers (Post Requirements) - Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/requirements/create', [OfferController::class, 'create'])->name('requirements.create');
    Route::post('/requirements', [OfferController::class, 'store'])->name('requirements.store');
    Route::get('/my-posts', [OfferController::class, 'index'])->name('my.posts');
});

// Wallet routes - Protected
Route::middleware('auth')->group(function () {
    Route::get('/wallet', [App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/add-funds', [App\Http\Controllers\WalletController::class, 'create'])->name('wallet.add-funds');
    Route::post('/wallet/add-funds', [App\Http\Controllers\WalletController::class, 'store'])->name('wallet.store');
});

// Notification routes - Protected
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::get('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
});

// Simple placeholders - protected
Route::middleware('auth')->group(function () {
    Route::view('/find-tutors', 'find-tutors')->name('find.tutors');
});
