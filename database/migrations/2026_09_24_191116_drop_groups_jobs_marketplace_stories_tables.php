<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Groups, Jobs, Marketplace, and Stories are being removed from the
     * product entirely (pivoting to a focused writing/publishing platform).
     * Confirmed as an intentional, non-reversible hard delete — down()
     * intentionally does not restore this schema; restore from a DB backup
     * or git history if needed.
     */
    public function up(): void
    {
        // Groups (children first, FK-safe order)
        Schema::dropIfExists('group_comment_likes');
        Schema::dropIfExists('group_post_comments');
        Schema::dropIfExists('group_post_likes');
        Schema::dropIfExists('group_resource_likes');
        Schema::dropIfExists('group_resources');
        Schema::dropIfExists('group_events');
        Schema::dropIfExists('group_activity_logs');
        Schema::dropIfExists('group_invitations');
        Schema::dropIfExists('group_members');
        Schema::dropIfExists('group_posts');
        Schema::dropIfExists('groups');

        // Marketplace
        Schema::dropIfExists('marketplace_saved_listings');
        Schema::dropIfExists('marketplace_saved_searches');
        Schema::dropIfExists('marketplace_reviews');
        Schema::dropIfExists('marketplace_interests');
        Schema::dropIfExists('marketplace_listing_images');
        Schema::dropIfExists('marketplace_listings');

        // Jobs (note: the queue's own `jobs` table is untouched — this app's
        // job board table is misspelled `jobbs`, see 2026_02_03_164820)
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('saved_jobs');
        Schema::dropIfExists('jobbs');

        // Stories
        Schema::dropIfExists('story_views');
        Schema::dropIfExists('stories');
    }

    public function down(): void
    {
        // Intentionally not reversible — see class docblock.
    }
};
