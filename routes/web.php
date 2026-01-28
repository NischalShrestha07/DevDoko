<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SaveController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return redirect()->route('welcome');
});

Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Logout (accessible to authenticated users only)
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Public Explore Page
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');

// Public Profile View
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show');


// Public Tag View
Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Home Feed
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Profile Management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Posts
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Post Interactions
    Route::post('/posts/{post}/like/toggle', [LikeController::class, 'toggle'])->name('posts.like.toggle');

    Route::post('/posts/{post}/save', [SaveController::class, 'store'])->name('posts.save');
    Route::delete('/posts/{post}/save', [SaveController::class, 'destroy'])->name('posts.unsave');

    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Follow System
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/follow', [FollowController::class, 'unfollow'])->name('users.unfollow');
    Route::get('/users/{user}/followers', [FollowController::class, 'followers'])->name('users.followers');
    Route::get('/users/{user}/following', [FollowController::class, 'following'])->name('users.following');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Saved Posts
    Route::get('/saved', [SaveController::class, 'index'])->name('saved.index');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// API Routes for AJAX
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('api.notifications.count');
    Route::get('/search/users', [SearchController::class, 'users'])->name('api.search.users');
});
