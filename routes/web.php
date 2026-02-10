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
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');
Route::get('/tech/{technology}', [TagController::class, 'techShow'])->name('tech.show');
Route::get('/tech-trending', [TagController::class, 'trending'])->name('tech.trending');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Posts - Resource Routes
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Post Interactions
    Route::post('/posts/{post}/like/toggle', [LikeController::class, 'toggle'])->name('posts.like.toggle');
    Route::post('/posts/{post}/save', [SaveController::class, 'store'])->name('posts.save');
    Route::delete('/posts/{post}/save', [SaveController::class, 'destroy'])->name('posts.unsave');
    Route::post('/posts/{post}/pin', [PostController::class, 'pin'])->name('posts.pin');
    Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');
    Route::post('/posts/{post}/report', [PostController::class, 'report'])->name('posts.report');

    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');

    // Follow System
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/follow', [FollowController::class, 'unfollow'])->name('users.unfollow');
    Route::get('/users/{user}/followers', [FollowController::class, 'followers'])->name('users.followers');
    Route::get('/users/{user}/following', [FollowController::class, 'following'])->name('users.following');

    // Home feed
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Profile Management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::post('/profile/cover', [ProfileController::class, 'updateCover'])->name('profile.cover.update');

    // Post Interactions
    Route::post('/posts/{post}/like/toggle', [LikeController::class, 'toggle'])->name('posts.like.toggle');
    Route::post('/posts/{post}/save', [SaveController::class, 'store'])->name('posts.save');
    Route::delete('/posts/{post}/save', [SaveController::class, 'destroy'])->name('posts.unsave');

    // Share and Report routes
    Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');
    Route::post('/posts/{post}/report', [PostController::class, 'report'])->name('posts.report');

    // Comments routes
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');

    // Posts - Additional routes
    Route::post('/posts/{post}/pin', [PostController::class, 'pin'])->name('posts.pin');
    Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');
    Route::post('/posts/{post}/report', [PostController::class, 'report'])->name('posts.report');


    // Feed routes
    Route::get('/feed', [HomeController::class, 'feed'])->name('feed');
    Route::get('/feed/following', [HomeController::class, 'following'])->name('feed.following');
    Route::get('/feed/popular', [HomeController::class, 'popular'])->name('feed.popular');
    Route::get('/feed/latest', [HomeController::class, 'latest'])->name('feed.latest');

    // Post collections
    Route::get('/collections', [SaveController::class, 'collections'])->name('collections.index');
    Route::post('/collections', [SaveController::class, 'createCollection'])->name('collections.create');
    Route::delete('/collections/{collection}', [SaveController::class, 'deleteCollection'])->name('collections.destroy');

    // Post drafts
    Route::get('/drafts', [PostController::class, 'drafts'])->name('posts.drafts');
    Route::post('/posts/{post}/publish', [PostController::class, 'publish'])->name('posts.publish');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Saved Posts
    Route::get('/saved', [SaveController::class, 'index'])->name('saved.index');

    Route::get('/users/{user}/saved', [ProfileController::class, 'saved'])->name('users.saved');
});
