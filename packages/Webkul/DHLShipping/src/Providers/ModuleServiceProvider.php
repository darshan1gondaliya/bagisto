<?php

namespace Webkul\DHLShipping\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\DHLShipping\Carriers\DHL;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'dhl');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'dhl');

        $this->app->resolving('shipping', function ($shipping) {
            $shipping->extend('dhl', function () {
                return new DHL();
            });
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/carriers.php', 'carriers'
        );

        $this->app->singleton('dhl_client', function ($app) {
            return new \Webkul\DHLShipping\Services\DHLClient();
        });
    }
}