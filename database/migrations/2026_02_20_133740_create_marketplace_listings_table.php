<?php
// database/migrations/2024_03_20_000001_create_marketplace_listings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marketplace_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('category', 100); // Simple string, not foreign key
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('price_type', ['fixed', 'negotiable', 'free'])->default('fixed');
            $table->enum('condition', ['new', 'like_new', 'good', 'fair', 'poor'])->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->json('specifications')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_shippable')->default(false);
            $table->boolean('is_local_pickup')->default(true);
            $table->enum('status', ['active', 'sold', 'reserved', 'expired'])->default('active');
            $table->integer('views_count')->default(0);
            $table->integer('interested_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_boosted')->default(false);
            $table->timestamp('boosted_until')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index(['category', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplace_listings');
    }
};
