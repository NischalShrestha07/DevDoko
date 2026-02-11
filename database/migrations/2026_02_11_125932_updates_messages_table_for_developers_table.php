<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Message types
            $table->string('type')->default('text')->after('content');
            $table->string('code_snippet')->nullable()->after('type');
            $table->string('code_language')->nullable()->after('code_snippet');
            $table->string('file_path')->nullable()->after('code_language');
            $table->string('file_name')->nullable()->after('file_path');
            $table->integer('file_size')->nullable()->after('file_name');

            // Message status
            $table->timestamp('delivered_at')->nullable()->after('read_at');
            $table->timestamp('deleted_for_sender_at')->nullable()->after('delivered_at');
            $table->timestamp('deleted_for_receiver_at')->nullable()->after('deleted_for_sender_at');

            // Reply/thread features
            $table->foreignId('reply_to_id')->nullable()->constrained('messages')->onDelete('set null');
            $table->boolean('is_thread_start')->default(false)->after('reply_to_id');

            // Reactions (for developers to 👍 code snippets)
            $table->json('reactions')->nullable()->after('is_thread_start');

            // Star/Bookmark important messages
            $table->boolean('is_starred_by_sender')->default(false);
            $table->boolean('is_starred_by_receiver')->default(false);
        });

        // Create message_reactions table for more complex reactions
        Schema::create('message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reaction'); // 👍, ❤️, 🎉, 🚀, 👨‍💻, etc.
            $table->timestamps();

            $table->unique(['message_id', 'user_id', 'reaction']);
        });

        // Create message_threads table for topic-based discussions
        Schema::create('message_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('participants')->nullable();
            $table->string('status')->default('active'); // active, archived, resolved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_reactions');
        Schema::dropIfExists('message_threads');

        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn([
                'type',
                'code_snippet',
                'code_language',
                'file_path',
                'file_name',
                'file_size',
                'delivered_at',
                'deleted_for_sender_at',
                'deleted_for_receiver_at',
                'reply_to_id',
                'is_thread_start',
                'reactions',
                'is_starred_by_sender',
                'is_starred_by_receiver'
            ]);
        });
    }
};
