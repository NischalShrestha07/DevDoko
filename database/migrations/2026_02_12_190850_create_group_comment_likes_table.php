<?php
// database/migrations/2024_01_01_000007_create_group_comment_likes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_post_comment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['group_post_comment_id', 'user_id'], 'group_comment_likes_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_comment_likes');
    }
};
