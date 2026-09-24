<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GithubAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SaveController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
// Project cover page is the entry point; it links on to the landing page.
// The landing page keeps the 'welcome' route name so existing links still work.
Route::get('/', [HomeController::class, 'cover'])->name('cover');
Route::get('/landing', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');
Route::get('/tech/{technology}', [TagController::class, 'techShow'])->name('tech.show');
Route::get('/tech-trending', [TagController::class, 'trending'])->name('tech.trending');

Route::get('/auth/github/redirect', [GithubAuthController::class, 'redirect'])->name('auth.github.redirect');
Route::get('/auth/github/callback', [GithubAuthController::class, 'callback'])->name('auth.github.callback');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update')->middleware('throttle:5,1');
});

// Authenticated routes
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Home feed
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Posts Routes
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('throttle:10,1');
    Route::post('/posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.upload-image');
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
    Route::post('/posts/{post}/hide', [PostController::class, 'hide'])->name('posts.hide');

    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('throttle:10,1');
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

    // Blocking
    Route::get('/settings/blocked', [BlockController::class, 'index'])->name('blocks.index');
    Route::post('/users/{user}/block', [BlockController::class, 'store'])->name('users.block');
    Route::delete('/users/{user}/block', [BlockController::class, 'destroy'])->name('users.unblock');

    // Profile Management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::post('/profile/cover', [ProfileController::class, 'updateCover'])->name('profile.cover.update');

    // Feed routes
    Route::get('/feed', [HomeController::class, 'feed'])->name('feed');
    Route::get('/feed/following', [HomeController::class, 'following'])->name('feed.following');
    Route::get('/feed/popular', [HomeController::class, 'popular'])->name('feed.popular');
    Route::get('/feed/latest', [HomeController::class, 'latest'])->name('feed.latest');

    // Post drafts
    Route::get('/drafts', [PostController::class, 'drafts'])->name('posts.drafts');
    Route::post('/posts/{post}/publish', [PostController::class, 'publish'])->name('posts.publish');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/search/all', [MessageController::class, 'search'])->name('messages.search');
    Route::get('/messages/unread/count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');
    Route::post('/messages/{message}/star', [MessageController::class, 'toggleStar'])->name('messages.star');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/{user}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/messages/{message}/reactions', [MessageController::class, 'addReaction'])->name('messages.reactions.store');
    Route::delete('/messages/{message}/reactions', [MessageController::class, 'removeReaction'])->name('messages.reactions.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/quick', [SearchController::class, 'quick'])->name('search.quick');

    // Saved Posts
    Route::get('/saved', [SaveController::class, 'index'])->name('saved.index');

    Route::get('/users/{user}/saved', [ProfileController::class, 'saved'])->name('users.saved');

    // Admin
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/users/{user}/toggle-verified', [AdminController::class, 'toggleVerified'])->name('admin.users.toggle-verified');
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::post('/admin/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->name('admin.reports.resolve');
    Route::post('/admin/reports/{report}/dismiss', [AdminController::class, 'dismissReport'])->name('admin.reports.dismiss');

    // Developers
    Route::get('/developers', [DeveloperController::class, 'index'])->name('developers.index');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{project}/fork', [ProjectController::class, 'fork'])->name('projects.fork');
    Route::post('/projects/{project}/like', [ProjectController::class, 'toggleLike'])->name('projects.like');
    Route::post('/projects/{project}/collaboration', [ProjectController::class, 'requestCollaboration'])->name('projects.request.collaboration');
    Route::post('/projects/collaborations/{collaboration}/approve', [ProjectController::class, 'approveCollaboration'])->name('projects.collaboration.approve');
    Route::post('/projects/collaborations/{collaboration}/reject', [ProjectController::class, 'rejectCollaboration'])->name('projects.collaboration.reject');
});
