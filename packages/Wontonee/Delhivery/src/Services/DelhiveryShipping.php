<?php

namespace Wontonee\Delhivery\Services;

use Webkul\Shipping\Contracts\ShippingMethod;
use Webkul\Checkout\Models\CartShippingRate;

class DelhiveryShipping implements ShippingMethod

{
    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->apiUrl = core()->getConfigData('sales.carriers.delhivery.api_url');
        $this->token = core()->getConfigData('sales.carriers.delhivery.api_token');
    }


    public function calculate()
    {
        return [
            [
                'method' => 'delhivery_standard',
                'method_title' => 'Standard (3-5 days)',
                'price' => 10.00,
                'description' => 'Delhivery Standard Shipping'
            ],
            [
                'method' => 'delhivery_express',
                'method_title' => 'Express (1-2 days)',
                'price' => 15.00,
                'description' => 'Delhivery Express Shipping'
            ]
        ];
    }

    public function calculateShippingRate($address, $items)
    {
        return [
            'carrier' => 'delhivery',
            'carrier_title' => 'Delhivery',
            'method' => 'standard',
            'method_title' => 'Standard Delivery',
            'price' => 10.00, // Fallback price
            'base_price' => 10.00
        ];
        // $cart = Cart::getCart();

        // $response = Http::withHeaders([
        //     'Authorization' => 'Token ' . $this->token,
        //     'Content-Type' => 'application/json'
        // ])->post($this->apiUrl . '/api/kinko/v1/invoice/charges/.json', [
        //     'pickup_location' => core()->getConfigData('sales.carriers.delhivery.warehouse_pincode'),
        //     'delivery_location' => $address->postcode,
        //     'weight' => $cart->all_items->sum('weight'),
        //     'payment_mode' => 'COD',
        //     'cod_amount' => $cart->grand_total,
        //     'client_name' => core()->getConfigData('sales.carriers.delhivery.client_name')
        // ]);

        // if ($response->successful()) {
        //     $data = $response->json();

        //     $shippingRate = new CartShippingRate;
        //     $shippingRate->carrier = 'delhivery';
        //     $shippingRate->carrier_title = 'Delhivery';
        //     $shippingRate->method = 'standard';
        //     $shippingRate->method_title = 'Standard Delivery';
        //     $shippingRate->price = $data['total_amount'];
        //     $shippingRate->base_price = $data['total_amount'];

        //     return $shippingRate;
        // }

        // return false;
    }

    public function createWaybill($order)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $this->token,
            'Content-Type' => 'application/json'
        ])->post($this->apiUrl . '/api/cmu/create.json', [
            'name' => $order->shipping_address->first_name . ' ' . $order->shipping_address->last_name,
            'order' => $order->id,
            'payment_mode' => $order->payment->method === 'cashondelivery' ? 'COD' : 'Prepaid',
            'total_amount' => $order->grand_total,
            'cod_amount' => $order->payment->method === 'cashondelivery' ? $order->grand_total : 0,
            'add' => $order->shipping_address->address1,
            'city' => $order->shipping_address->city,
            'state' => $order->shipping_address->state,
            'country' => $order->shipping_address->country,
            'phone' => $order->shipping_address->phone,
            'pin' => $order->shipping_address->postcode,
            'quantity' => $order->items->count(),
            'shipment_width' => 10,
            'shipment_height' => 10,
            'shipment_length' => 10,
            'weight' => $order->items->sum('weight')
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }

    public function trackShipment($waybill)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $this->token,
        ])->get($this->apiUrl . '/api/p/packing_slip', [
            'wbns' => $waybill
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }
}
