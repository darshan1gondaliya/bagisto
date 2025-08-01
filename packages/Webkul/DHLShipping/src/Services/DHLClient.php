<?php

namespace Webkul\DHLShipping\Services;

use Illuminate\Support\Facades\Http;

class DHLClient
{
    protected $sandboxUrl = 'https://express.api.dhl.com/mydhlapi/test';
    protected $productionUrl = 'https://express.api.dhl.com/mydhlapi';

    public function getRates($accountNumber, $postcode, $weight, $orderTotal)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode(
                $this->getConfig('api_key').':'.$this->getConfig('api_secret')
            ),
            'Content-Type' => 'application/json'
        ])->post($this->getApiUrl().'/rates', [
            'accountNumber' => $accountNumber,
            'originPostalCode' => $this->getConfig('warehouse_postcode'),
            'destinationPostalCode' => $postcode,
            'weight' => $weight,
            'totalValue' => $orderTotal,
            'isCustomsDeclarable' => false
        ]);
        //dd($response->json());
        if ($response->successful()) {
            return $this->parseRates($response->json());
        }

        throw new \Exception("DHL API Error: ".$response->body());
    }

    protected function parseRates($response)
    {
        return collect($response['products'] ?? [])->map(function ($product) {
            return [
                'method' => strtolower($product['productCode']),
                'title' => $product['productName'],
                'price' => $product['totalPrice'][0]['price'],
                'delivery_time' => $product['deliveryCapabilities']['estimatedDeliveryDateAndTime']
            ];
        })->toArray();
    }

    protected function getApiUrl()
    {
        return $this->getConfig('sandbox') ? $this->sandboxUrl : $this->productionUrl;
    }

    protected function getConfig($key)
    {
        return core()->getConfigData('sales.carriers.dhl.'.$key);
    }
}