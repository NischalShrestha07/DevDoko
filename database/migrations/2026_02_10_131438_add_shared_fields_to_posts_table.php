<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('shared_post_id')->nullable()->after('user_id');
            $table->json('share_details')->nullable()->after('shared_post_id');

            // Add foreign key constraint
            $table->foreign('shared_post_id')->references('id')->on('posts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['shared_post_id']);
            $table->dropColumn(['shared_post_id', 'share_details']);
        });
    }
};
