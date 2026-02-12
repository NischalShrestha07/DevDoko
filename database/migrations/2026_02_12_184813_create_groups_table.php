<?php
// database/migrations/2024_01_01_000001_create_groups_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category');
            $table->json('tags')->nullable();
            $table->string('icon')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('privacy', ['public', 'private', 'hidden'])->default('public');
            $table->enum('member_approval', ['anyone', 'admin_approval', 'invite_only'])->default('anyone');
            $table->json('settings')->nullable();
            $table->integer('members_count')->default(0);
            $table->integer('posts_count')->default(0);
            $table->integer('pending_requests')->default(0);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'members_count']);
            $table->index(['privacy', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('groups');
    }
};
