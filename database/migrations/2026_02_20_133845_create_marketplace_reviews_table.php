<?php
// database/migrations/2024_03_20_000005_create_marketplace_reviews_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marketplace_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('marketplace_listings')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating')->unsigned()->min(1)->max(5);
            $table->text('comment')->nullable();
            $table->json('criteria')->nullable(); // communication, shipping, accuracy
            $table->timestamps();

            $table->unique(['listing_id', 'buyer_id']);
            $table->index(['seller_id', 'rating']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplace_reviews');
    }
};
