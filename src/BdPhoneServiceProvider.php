<?php

namespace Sanvalabs\BdPhone;

use Illuminate\Support\ServiceProvider;

class BdPhoneServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bd-phone.php', 'bd-phone');

        $this->app->singleton(PhoneNumber::class, fn ($app) => new PhoneNumber(
            $app['config']->get('bd-phone.operators'),
        ));
    }

    public function boot(): void
    {
        // Messages are used as trans('bd-phone::validation.invalid')
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'bd-phone');

        $this->publishes([
            __DIR__.'/../config/bd-phone.php' => config_path('bd-phone.php'),
        ], 'bd-phone-config');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/bd-phone'),
        ], 'bd-phone-lang');
    }
}