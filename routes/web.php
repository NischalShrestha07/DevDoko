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
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SaveController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupPostController;
use App\Http\Controllers\GroupResourceController;
use App\Http\Controllers\GroupEventController;

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

Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::get('/groups/discover', [GroupController::class, 'discover'])->name('groups.discover');
Route::get('/groups/trending', [GroupController::class, 'trending'])->name('groups.trending');
Route::get('/groups/categories/{category}', [GroupController::class, 'category'])->name('groups.category');
Route::get('/groups/invitation/{token}', [GroupController::class, 'acceptInvitation'])->name('groups.accept-invitation');

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

    // Home feed
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Posts Routes
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
    // Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('follow.toggle');



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
    Route::get('/messages/search/all', [MessageController::class, 'search'])->name('messages.search');
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

    // Saved Posts
    Route::get('/saved', [SaveController::class, 'index'])->name('saved.index');

    Route::get('/users/{user}/saved', [ProfileController::class, 'saved'])->name('users.saved');

    // Developers
    Route::get('/developers', [DeveloperController::class, 'index'])->name('developers.index');

    // ============= GROUP ROUTES =============
    Route::get('/my-groups', [GroupController::class, 'myGroups'])->name('groups.my-groups');
    Route::get('/groups/recommended', [GroupController::class, 'recommended'])->name('groups.recommended');

    // Create Group
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');

    // Single Group Routes (using slug)
    Route::prefix('groups/{group:slug}')->group(function () {
        // View group
        Route::get('/', [GroupController::class, 'show'])->name('groups.show');

        // Manage group
        Route::get('/edit', [GroupController::class, 'edit'])->name('groups.edit');
        Route::put('/', [GroupController::class, 'update'])->name('groups.update');
        Route::delete('/', [GroupController::class, 'destroy'])->name('groups.destroy');

        // Membership
        Route::post('/join', [GroupController::class, 'join'])->name('groups.join');
        Route::delete('/leave', [GroupController::class, 'leave'])->name('groups.leave');
        Route::get('/members', [GroupController::class, 'members'])->name('groups.members');
        Route::post('/members/{user}/approve', [GroupController::class, 'approveMember'])->name('groups.members.approve');
        Route::delete('/members/{user}/reject', [GroupController::class, 'rejectMember'])->name('groups.members.reject');
        Route::delete('/members/{user}/remove', [GroupController::class, 'removeMember'])->name('groups.members.remove');
        Route::put('/members/{user}/role', [GroupController::class, 'updateMemberRole'])->name('groups.members.role');

        // Invitations
        Route::post('/invite', [GroupController::class, 'invite'])->name('groups.invite');

        // Posts
        Route::post('/posts', [GroupController::class, 'storePost'])->name('groups.posts.store');
        Route::get('/posts/{post}', [GroupController::class, 'showPost'])->name('groups.post');
        Route::post('/posts/{post}/like', [GroupController::class, 'likePost'])->name('groups.posts.like');
        Route::post('/posts/{post}/pin', [GroupController::class, 'pinPost'])->name('groups.posts.pin');
        Route::post('/posts/{post}/unpin', [GroupController::class, 'unpinPost'])->name('groups.posts.unpin');

        // Post Comments
        Route::post('/posts/{post}/comments', [GroupController::class, 'storeComment'])->name('groups.posts.comments.store');
        Route::delete('/comments/{comment}', [GroupController::class, 'deleteComment'])->name('groups.comments.destroy');
        Route::post('/comments/{comment}/like', [GroupController::class, 'likeComment'])->name('groups.comments.like');

        // Resources
        Route::get('/resources', [GroupController::class, 'resources'])->name('groups.resources');
        Route::post('/resources', [GroupController::class, 'storeResource'])->name('groups.resources.store');
        Route::get('/resources/{resource}/download', [GroupController::class, 'downloadResource'])->name('groups.resources.download');
        Route::delete('/resources/{resource}', [GroupController::class, 'deleteResource'])->name('groups.resources.destroy');
        Route::post('/resources/{resource}/like', [GroupController::class, 'likeResource'])->name('groups.resources.like');

        // Events
        Route::get('/events', [GroupController::class, 'events'])->name('groups.events');
        Route::post('/events', [GroupController::class, 'storeEvent'])->name('groups.events.store');
        Route::delete('/events/{event}', [GroupController::class, 'deleteEvent'])->name('groups.events.destroy');
        Route::post('/events/{event}/attend', [GroupController::class, 'attendEvent'])->name('groups.events.attend');

        // Activity
        Route::get('/activity', [GroupController::class, 'activity'])->name('groups.activity');
    });


    // Group categories and discovery
    Route::get('/groups/discover', [GroupController::class, 'discover'])->name('groups.discover');
    Route::get('/groups/recommended', [GroupController::class, 'recommended'])->name('groups.recommended');
    Route::get('/groups/trending', [GroupController::class, 'trending'])->name('groups.trending');
});

// Admin routes (if you have admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/groups', [AdminController::class, 'groups'])->name('groups');
    Route::get('/groups/{group}', [AdminController::class, 'showGroup'])->name('groups.show');
    Route::delete('/groups/{group}', [AdminController::class, 'deleteGroup'])->name('groups.delete');
    Route::post('/groups/{group}/feature', [AdminController::class, 'featureGroup'])->name('groups.feature');
});
