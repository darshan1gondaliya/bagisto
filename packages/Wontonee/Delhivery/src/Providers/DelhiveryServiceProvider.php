<?php

namespace Wontonee\Delhivery\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Wontonee\Delhivery\Services\DelhiveryShipping;
use Wontonee\Delhivery\Contracts\DelhiveryShipping as DelhiveryShippingContract;

class DelhiveryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('delhivery_shipping', function ($app) {
            return new \Wontonee\Delhivery\Carriers\Delhivery();
        });
        
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/carriers.php', 'carriers'
        );
        
        Event::listen('bagisto.shipping.init', function($shipping) {
            $shipping->carriers()->put('delhivery', [
                'title' => 'Delhivery',
                'class' => \Wontonee\Delhivery\Carriers\Delhivery::class
            ]);
        });
    }
}