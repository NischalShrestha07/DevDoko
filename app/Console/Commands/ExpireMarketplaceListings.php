<?php

namespace App\Console\Commands;

use App\Models\MarketplaceListing;
use Illuminate\Console\Command;

class ExpireMarketplaceListings extends Command
{
    protected $signature = 'marketplace:expire-listings';

    protected $description = 'Mark expired marketplace listings as expired';

    public function handle()
    {
        $expiredCount = MarketplaceListing::whereIn('status', ['active', 'reserved'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $this->info("Marked {$expiredCount} listings as expired.");

        return Command::SUCCESS;
    }
}
