<?php

namespace Webkul\DHLShipping\Http\Controllers\Admin;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\DHLShipping\Services\DHLClient;

class DHLController extends Controller
{
    public function testConnection()
    {
        try {
            $response = app(DHLClient::class)->getRates(
                core()->getConfigData('sales.carriers.dhl.account_number'),
                '10001', // Test destination postcode
                2.5, // Test weight
                100 // Test order value
            );

            return response()->json([
                'status' => true,
                'rates' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}