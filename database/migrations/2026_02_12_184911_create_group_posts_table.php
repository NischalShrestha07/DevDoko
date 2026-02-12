<?php
// database/migrations/2024_01_01_000004_create_group_posts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->nullable()->constrained('posts')->onDelete('set null');
            $table->string('title');
            $table->text('content')->nullable();
            $table->enum('type', ['general', 'announcement', 'question', 'resource', 'event', 'job'])->default('general');
            $table->json('attachments')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_important')->default(false);
            $table->timestamp('pinned_until')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['group_id', 'is_pinned', 'created_at']);
            $table->index(['group_id', 'type', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_posts');
    }
};
