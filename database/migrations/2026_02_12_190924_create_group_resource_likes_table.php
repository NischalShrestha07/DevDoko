<?php
// database/migrations/2024_01_01_000009_create_group_resource_likes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_resource_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_resource_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['group_resource_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_resource_likes');
    }
};
