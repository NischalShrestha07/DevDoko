<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drops tables with no model/controller/view reference anywhere in the app:
     * collaborations/* was superseded by project_collaborations, message_threads
     * and code_snippets were never built past the migration, marketplace_categories
     * was never used (categories are a free-text column on marketplace_listings).
     */
    public function up(): void
    {
        Schema::dropIfExists('collaboration_applications');
        Schema::dropIfExists('collaboration_participants');
        Schema::dropIfExists('collaborations');
        Schema::dropIfExists('message_threads');
        Schema::dropIfExists('code_snippets');
        Schema::dropIfExists('marketplace_categories');
    }

    public function down(): void
    {
        Schema::create('collaborations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->json('required_skills');
            $table->integer('team_size');
            $table->integer('current_size')->default(1);
            $table->enum('timeline', ['week', 'month', 'quarter', 'flexible']);
            $table->boolean('is_paid')->default(false);
            $table->decimal('budget', 10, 2)->nullable();
            $table->enum('budget_type', ['hourly', 'fixed', 'bounty'])->nullable();
            $table->enum('status', ['open', 'in_progress', 'completed', 'closed'])->default('open');
            $table->timestamps();
        });

        Schema::create('collaboration_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collaboration_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role');
            $table->timestamp('joined_at');
            $table->timestamps();
        });

        Schema::create('collaboration_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collaboration_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('message_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('participants')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('code_snippets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('language');
            $table->longText('code');
            $table->timestamps();
        });

        Schema::create('marketplace_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
