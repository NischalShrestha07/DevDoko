<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jobbs', function (Blueprint $table) {
            $table->string('type', 50)->change();
            $table->string('location_type', 50)->change();
            $table->string('experience_level', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('jobbs', function (Blueprint $table) {
            $table->string('type', 50)->change();
            $table->string('location_type', 50)->change();
            $table->string('experience_level', 50)->change();
        });
    }
};
