<?php

namespace App\Providers;

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
        \App\Models\RaidEvent::observe(\App\Observers\RaidEventObserver::class);
        \App\Models\PaymentRequest::observe(\App\Observers\PaymentRequestObserver::class);

        \Illuminate\Support\Facades\Event::listen(
            \SocialiteProviders\Manager\SocialiteWasCalled::class,
            [\SocialiteProviders\Discord\DiscordExtendSocialite::class, 'handle']
        );
    }
}
