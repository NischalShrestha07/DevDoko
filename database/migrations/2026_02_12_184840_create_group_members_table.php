<?php
// database/migrations/2024_01_01_000002_create_group_members_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['owner', 'admin', 'moderator', 'member'])->default('member');
            $table->enum('status', ['active', 'pending', 'banned', 'left'])->default('active');
            $table->timestamp('joined_at');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->integer('contributions_count')->default(0);
            $table->json('badges')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->unique(['group_id', 'user_id']);
            $table->index(['group_id', 'role']);
            $table->index(['group_id', 'status']);
            $table->index(['user_id', 'joined_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_members');
    }
};
