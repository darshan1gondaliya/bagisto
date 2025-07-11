<?php

return [
    [
        'key' => 'sales.carriers.dhl',
        'name' => 'DHL Shipping',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'Method Title',
                'type' => 'text',
                'validation' => 'required',
                'default' => 'DHL Express'
            ],
            [
                'name' => 'active',
                'title' => 'Status',
                'type' => 'boolean',
                'validation' => 'required',
                'default' => true
            ],
            [
                'name' => 'account_number',
                'title' => 'DHL Account Number',
                'type' => 'text',
                'validation' => 'required'
            ],
            [
                'name' => 'api_key',
                'title' => 'API Key',
                'type' => 'password',
                'validation' => 'required'
            ],
            [
                'name' => 'api_secret',
                'title' => 'API Secret',
                'type' => 'password',
                'validation' => 'required'
            ],
            [
                'name' => 'warehouse_postcode',
                'title' => 'Origin Postcode',
                'type' => 'text',
                'validation' => 'required'
            ],
            [
                'name' => 'sandbox',
                'title' => 'Sandbox Mode',
                'type' => 'boolean',
                'default' => true
            ],
            [
                'name' => 'default_rate',
                'title' => 'Default Rate (INR)',
                'type' => 'text',
                'validation' => 'numeric',
                'default' => '15.00'
            ],
            [
                'name' => 'economy_rate',
                'title' => 'Economy Rate (INR)',
                'type' => 'text',
                'validation' => 'numeric',
                'default' => '10.00'
            ]
        ]
    ]
];