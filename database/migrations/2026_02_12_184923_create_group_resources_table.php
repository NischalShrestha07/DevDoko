<?php
// database/migrations/2024_01_01_000008_create_group_resources_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['link', 'file', 'code', 'tutorial', 'tool', 'book'])->default('link');
            $table->string('url')->nullable();
            $table->string('file_path')->nullable();
            $table->json('metadata')->nullable();
            $table->json('tags')->nullable();
            $table->integer('downloads_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->timestamps();

            $table->index(['group_id', 'type', 'created_at']);
            $table->index(['group_id', 'downloads_count']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_resources');
    }
};
