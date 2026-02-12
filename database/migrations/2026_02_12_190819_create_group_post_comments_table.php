<?php
// database/migrations/2024_01_01_000006_create_group_post_comments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('group_post_comments')->onDelete('cascade');
            $table->text('content');
            $table->integer('likes_count')->default(0);
            $table->timestamps();

            $table->index(['group_post_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_post_comments');
    }
};
