<?php
// database/migrations/2024_03_20_000004_create_marketplace_saved_listings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marketplace_saved_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('listing_id')->constrained('marketplace_listings')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'listing_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplace_saved_listings');
    }
};
