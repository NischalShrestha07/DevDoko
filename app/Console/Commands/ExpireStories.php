<?php

namespace App\Console\Commands;

use App\Models\Story;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExpireStories extends Command
{
    protected $signature = 'stories:expire';

    protected $description = 'Delete expired stories and their media files';

    public function handle()
    {
        $expired = Story::where('expires_at', '<', now())->get();

        foreach ($expired as $story) {
            Storage::disk('public')->delete($story->media_path);
            $story->delete();
        }

        $this->info("Deleted {$expired->count()} expired stories.");

        return Command::SUCCESS;
    }
}
