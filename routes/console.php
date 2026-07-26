<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('marketplace:expire-listings', function () {
    $this->call(\App\Console\Commands\ExpireMarketplaceListings::class);
})->purpose('Expire stale marketplace listings');

Schedule::command('marketplace:expire-listings')->daily();
