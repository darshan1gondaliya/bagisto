<?php

namespace Webkul\DHLShipping\Carriers;

use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\DHLShipping\Services\DHLClient;
use Webkul\Checkout\Facades\Cart;

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
            $this->calculateCartWeight($cart),
            $cart->grand_total
        );
    }

    protected function calculateCartWeight($cart)
    {
        $totalWeight = 0;
        
        foreach ($cart->items as $item) {
            // For simple products
            if ($item->type === 'simple' && $item->product) {
                $totalWeight += $this->getProductWeight($item->product) * $item->quantity;
            }
            // For configurable products
            elseif ($item->type === 'configurable' && $item->child && $item->child->product) {
                $totalWeight += $this->getProductWeight($item->child->product) * $item->quantity;
            }
        }
        
        return $totalWeight;
    }

    protected function getProductWeight($product)
    {
        // Check if weight attribute exists directly on product
        if (isset($product->weight)) {
            return $product->weight;
        }
        
        // Fallback to checking product attributes
        if ($product->attribute_family) {
            $weightAttribute = $product->attribute_family->custom_attributes()
                ->where('code', 'weight')
                ->first();
                
            if ($weightAttribute) {
                return $product->{$weightAttribute->code} ?? 0;
            }
        }
        
        return 0; // Default weight if not found
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