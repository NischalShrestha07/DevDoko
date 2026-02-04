<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
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
    }

    public function down()
    {
        Schema::dropIfExists('collaboration_applications');
        Schema::dropIfExists('collaboration_participants');
        Schema::dropIfExists('collaborations');
    }
};
