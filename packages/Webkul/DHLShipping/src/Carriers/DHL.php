<?php

namespace Webkul\DHLShipping\Carriers;

use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\DHLShipping\Services\DHLClient;
use Webkul\Checkout\Models\Cart;

class DHL extends AbstractShipping
{
    protected $code = 'dhl';

    public function calculate()
    {
        if (!$this->isAvailable()) {
            return false;
        }

        try {
            $rates = $this->getDHLRates();
            return $rates ?: $this->getFallbackRates();
        } catch (\Exception $e) {
            logger()->error('DHL Error: '.$e->getMessage());
            return $this->getFallbackRates();
        }
    }

    protected function getDHLRates()
    {
        $cart = Cart::getCart();
        
        return app(DHLClient::class)->getRates(
            $this->getConfigData('account_number'),
            $cart->shipping_address->postcode,
            $cart->getCartWeight(),
            $cart->grand_total
        );
    }

    protected function getFallbackRates()
    {
        return [
            $this->buildRate('standard', 'DHL Express', $this->getConfigData('default_rate')),
            $this->buildRate('economy', 'DHL Economy', $this->getConfigData('economy_rate'))
        ];
    }

    protected function buildRate($method, $title, $price)
    {
        $rate = new CartShippingRate;
        $rate->carrier = $this->code;
        $rate->carrier_title = $this->getConfigData('title');
        $rate->method = "{$this->code}_{$method}";
        $rate->method_title = $title;
        $rate->price = $price;
        $rate->base_price = $price;
        return $rate;
    }
}