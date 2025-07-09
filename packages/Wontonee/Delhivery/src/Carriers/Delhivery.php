<?php

namespace Wontonee\Delhivery\Carriers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;
use Illuminate\Support\Facades\Http;

class Delhivery extends AbstractShipping
{
    /**
     * Shipping method code
     *
     * @var string
     */
    protected $code = 'delhivery';


    protected $method = 'delhivery_delhivery';

    /**
     * Calculate shipping rates
     *
     * @return CartShippingRate|array|bool
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        $rates = $this->getRates();

        return empty($rates) ? false : $rates;
    }

    /**
     * Get available shipping rates
     *
     * @return array
     */
    protected function getRates()
    {
        $rates = [];

        try {
            $apiResponse = $this->getApiRates();
            
            // Standard rate
            $rates[] = $this->buildRate(
                'standard',
                'Standard Delivery (3-5 days)',
                $apiResponse['standard_rate'] ?? $this->getConfigData('default_rate')
            );

            // Express rate
            if ($this->getConfigData('express_enabled')) {
                $rates[] = $this->buildRate(
                    'express',
                    'Express Delivery (1-2 days)',
                    $apiResponse['express_rate'] ?? $this->getConfigData('express_rate')
                );
            }
        } catch (\Exception $e) {
            logger()->error('Delhivery API Error: ' . $e->getMessage());
            
            // Fallback rates
            $rates[] = $this->buildRate(
                'standard',
                'Standard Delivery',
                $this->getConfigData('default_rate')
            );
        }

        return $rates;
    }

    /**
     * Build rate object
     */
    protected function buildRate($method, $title, $price): CartShippingRate
    {
        $rate = new CartShippingRate;
        $rate->carrier = $this->code;
        $rate->carrier_title = $this->getConfigData('title');
        $rate->method = $this->code . '_' . $method;
        $rate->method_title = $title;
        $rate->method_description = $this->getConfigData('description');
        $rate->price = $price;
        $rate->base_price = $price;
        
        return $rate;
    }

    /**
     * Get rates from Delhivery API
     */
    protected function getApiRates(): array
    {
        $cart = Cart::getCart();

        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $this->getConfigData('api_token'),
            'Accept' => 'application/json'
        ])->post($this->getConfigData('api_url') . '/api/kinko/v1/invoice/charges/.json', [
            'pickup_location' => $this->getConfigData('warehouse_pincode'),
            'delivery_location' => $cart->shipping_address->postcode,
            'weight' => $cart->items->sum('weight'),
            'payment_mode' => $cart->payment->method === 'cashondelivery' ? 'COD' : 'Prepaid',
            'cod_amount' => $cart->payment->method === 'cashondelivery' ? $cart->grand_total : 0,
            'client_name' => $this->getConfigData('client_name')
        ]);

        if (! $response->successful()) {
            throw new \Exception($response->body());
        }

        return $response->json();
    }
}