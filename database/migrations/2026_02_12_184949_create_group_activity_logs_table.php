<?php
// database/migrations/2024_01_01_000011_create_group_activity_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('data')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['group_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_activity_logs');
    }
};
