<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminCommunityAccountController;
use App\Http\Controllers\AdminCommunityStoryController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CommunityAccountLoginController;
use App\Http\Controllers\CommunityAccountRequestController;
use App\Http\Controllers\CommunityStoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $publishedStories = DB::table('community_stories')
        ->where('status', 'published')
        ->latest('reviewed_at')
        ->latest('created_at')
        ->limit(3)
        ->get();

    return view('welcome', compact('publishedStories'));
})->name('home');

Route::get('/buat-akun', [CommunityAccountRequestController::class, 'create'])->name('community-account.create');
Route::post('/buat-akun', [CommunityAccountRequestController::class, 'store'])->name('community-account.store');
Route::get('/akun/masuk', [CommunityAccountLoginController::class, 'create'])->name('community-login.create');
Route::post('/akun/masuk', [CommunityAccountLoginController::class, 'store'])->name('community-login.store');
Route::post('/akun/keluar', [CommunityAccountLoginController::class, 'destroy'])->name('community-login.destroy');
Route::get('/tambah-cerita', [CommunityStoryController::class, 'create'])->name('community-stories.create');
Route::post('/tambah-cerita', [CommunityStoryController::class, 'store'])->name('community-stories.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', function () {
        $stats = [
            'communities' => DB::table('literacy_communities')->count(),
            'posts' => DB::table('literacy_posts')->count(),
            'active_programs' => DB::table('literacy_programs')->whereIn('status', ['planned', 'active'])->count(),
            'members' => DB::table('community_members')->count(),
        ];

        $activities = DB::table('activity_reports')
            ->join('literacy_communities', 'activity_reports.literacy_community_id', '=', 'literacy_communities.id')
            ->select('activity_reports.title', 'activity_reports.activity_date', 'activity_reports.status', 'literacy_communities.name as community_name')
            ->latest('activity_reports.created_at')
            ->limit(3)
            ->get();

        return view('dashboard', compact('stats', 'activities'));
    })->name('dashboard');

    Route::get('/dashboard/komunitas', [AdminCommunityAccountController::class, 'index'])->name('admin.communities.index');
    Route::get('/dashboard/cerita', [AdminCommunityStoryController::class, 'index'])->name('admin.stories.index');
    Route::patch('/dashboard/cerita/{story}/approve', [AdminCommunityStoryController::class, 'approve'])->name('admin.stories.approve');
    Route::patch('/dashboard/cerita/{story}/reject', [AdminCommunityStoryController::class, 'reject'])->name('admin.stories.reject');
    Route::get('/dashboard/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/dashboard/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/dashboard/users', [AdminUserController::class, 'store'])->name('admin.users.store');
});
