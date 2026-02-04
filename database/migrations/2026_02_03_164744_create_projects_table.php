<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('forked_from_id')->nullable()->constrained('projects');
            $table->string('title');
            $table->string('short_description', 300);
            $table->text('description');
            $table->string('repository_url')->nullable();
            $table->string('live_url')->nullable();
            $table->json('technologies');
            $table->string('category');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced', 'expert']);
            $table->boolean('is_public')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['planning', 'active', 'completed', 'archived'])->default('active');
            $table->string('thumbnail_path')->nullable();
            $table->json('screenshots')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('forks_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
