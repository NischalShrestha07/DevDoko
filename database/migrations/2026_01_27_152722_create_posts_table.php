<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['text', 'code', 'image', 'question', 'project', 'status', 'video', 'share', 'link']);
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->text('code_snippet')->nullable();
            $table->string('code_language')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('visibility', ['public', 'private', 'followers'])->default('public');
            $table->boolean('is_pinned')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->string('video_path')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_title')->nullable();
            $table->string('link_description')->nullable();
            $table->string('link_image')->nullable();
            $table->integer('reading_time')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index(['visibility', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
