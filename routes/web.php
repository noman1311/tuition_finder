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
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Tuition offers (Post Requirements)
Route::middleware('web')->group(function () {
    Route::get('/requirements/create', [OfferController::class, 'create'])->name('requirements.create');
    Route::post('/requirements', [OfferController::class, 'store'])->name('requirements.store');
    Route::get('/my-posts', [OfferController::class, 'index'])->name('my.posts');
});

// Simple placeholders
Route::view('/find-tutors', 'find-tutors')->name('find.tutors');
Route::view('/wallets', 'wallets')->name('wallets');
Route::view('/notifications', 'notifications')->name('notifications');
