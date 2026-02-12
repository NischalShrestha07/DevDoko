<?php
// database/migrations/2024_01_01_000003_create_group_invitations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('inviter_id')->constrained('users');
            $table->string('email')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('token')->unique();
            $table->enum('status', ['pending', 'accepted', 'declined', 'expired'])->default('pending');
            $table->text('message')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['group_id', 'status']);
            $table->index(['email', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_invitations');
    }
};
