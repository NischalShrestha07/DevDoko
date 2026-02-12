<?php
// database/migrations/2024_01_01_000010_create_group_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['meetup', 'hackathon', 'workshop', 'webinar', 'social'])->default('meetup');
            $table->enum('format', ['online', 'in_person', 'hybrid'])->default('online');
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->integer('attendees_count')->default(0);
            $table->integer('max_attendees')->nullable();
            $table->json('attendees')->nullable();
            $table->json('waitlist')->nullable();
            $table->timestamps();

            $table->index(['group_id', 'starts_at']);
            $table->index(['type', 'starts_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_events');
    }
};
