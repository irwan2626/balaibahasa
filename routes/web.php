<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminCommunityAccountController;
use App\Http\Controllers\AdminCommunityStoryController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CommunityAccountLoginController;
use App\Http\Controllers\CommunityAccountRequestController;
use App\Http\Controllers\CommunityProfileController;
use App\Http\Controllers\CommunityStoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/komunitas', [HomeController::class, 'communities'])->name('communities.index');
Route::get('/komunitas/{community}', [HomeController::class, 'showCommunity'])->name('communities.show');
Route::get('/articles', [HomeController::class, 'articles'])->name('articles.index');
Route::get('/cerita/{story}', [HomeController::class, 'showStory'])->name('stories.show');

Route::get('/buat-akun', [CommunityAccountRequestController::class, 'create'])->name('community-account.create');
Route::post('/buat-akun', [CommunityAccountRequestController::class, 'store'])->name('community-account.store');
Route::get('/akun/masuk', [CommunityAccountLoginController::class, 'create'])->name('community-login.create');
Route::post('/akun/masuk', [CommunityAccountLoginController::class, 'store'])->name('community-login.store');
Route::post('/akun/keluar', [CommunityAccountLoginController::class, 'destroy'])->name('community-login.destroy');
Route::get('/akun/profil', [CommunityProfileController::class, 'show'])->name('community-profile.show');
Route::patch('/akun/profil', [CommunityProfileController::class, 'update'])->name('community-profile.update');
Route::get('/tambah-cerita', [CommunityStoryController::class, 'create'])->name('community-stories.create');
Route::post('/tambah-cerita', [CommunityStoryController::class, 'store'])->name('community-stories.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/komunitas', [AdminCommunityAccountController::class, 'index'])->name('admin.communities.index');
    Route::patch('/dashboard/komunitas/{community}/approve', [AdminCommunityAccountController::class, 'approve'])->name('admin.communities.approve');
    Route::patch('/dashboard/komunitas/{community}/reject', [AdminCommunityAccountController::class, 'reject'])->name('admin.communities.reject');
    Route::delete('/dashboard/komunitas/{community}', [AdminCommunityAccountController::class, 'destroy'])->name('admin.communities.destroy');
    Route::get('/dashboard/cerita', [AdminCommunityStoryController::class, 'index'])->name('admin.stories.index');
    Route::get('/dashboard/cerita/{story}', [AdminCommunityStoryController::class, 'show'])->name('admin.stories.show');
    Route::patch('/dashboard/cerita/{story}/comment', [AdminCommunityStoryController::class, 'comment'])->name('admin.stories.comment');
    Route::patch('/dashboard/cerita/{story}/approve', [AdminCommunityStoryController::class, 'approve'])->name('admin.stories.approve');
    Route::patch('/dashboard/cerita/{story}/reject', [AdminCommunityStoryController::class, 'reject'])->name('admin.stories.reject');
    Route::get('/dashboard/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/dashboard/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/dashboard/users', [AdminUserController::class, 'store'])->name('admin.users.store');
});
