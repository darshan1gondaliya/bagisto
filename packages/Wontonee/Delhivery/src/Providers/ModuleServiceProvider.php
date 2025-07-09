<?php
namespace Wontonee\Delhivery\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Wontonee\Delhivery\Services\DelhiveryShipping;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'delhivery');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'delhivery');
    
         // Load admin routes
        $this->loadRoutesFrom(__DIR__.'/../Http/admin-routes.php');

        // Register shipping method through Bagisto's carrier system
        $this->app->singleton('delhivery_shipping', function ($app) {
            return new \Wontonee\Delhivery\Services\DelhiveryShipping();
        });
    
        Event::listen('bagisto.shipping.init', function($shipping) {
            $shipping->carriers()->put('delhivery', [
                'title' => 'Delhivery',
                'class' => 'delhivery_shipping'
            ]);
        });
    }
    
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );
    }
}