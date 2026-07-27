<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Renders user-authored text: markdown -> link @mentions and #hashtags.
        //
        // Escaping is delegated to CommonMark via html_input=escape rather than
        // wrapping in e() first. e() + markdown double-escapes, so a quote in a
        // code snippet came out as the literal &quot;. allow_unsafe_links
        // blocks javascript: hrefs.
        Blade::directive('rich', function ($expression) {
            return "<?php echo \App\Support\ContentParser::linkify(\Illuminate\Support\Str::markdown($expression ?? '', ['html_input' => 'escape', 'allow_unsafe_links' => false])); ?>";
        });
    }
}
