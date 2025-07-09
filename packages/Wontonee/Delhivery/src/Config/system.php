<?php

return [
    [
        'key' => 'sales.carriers.delhivery',
        'name' => 'Delhivery Shipping',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'Method Title',
                'type' => 'text',
                'validation' => 'required',
                'default' => 'Delhivery'
            ],
            [
                'name' => 'description',
                'title' => 'Description',
                'type' => 'textarea'
            ],
            [
                'name' => 'active',
                'title' => 'Status',
                'type' => 'boolean',
                'validation' => 'required',
                'default' => true
            ],
            [
                'name' => 'default_rate',
                'title' => 'Default Rate',
                'type' => 'text',
                'validation' => 'decimal',
                'default' => '10.00'
            ],
            [
                'name' => 'express_enabled',
                'title' => 'Enable Express Shipping',
                'type' => 'boolean',
                'default' => true
            ],
            [
                'name' => 'express_rate',
                'title' => 'Express Rate',
                'type' => 'text',
                'validation' => 'decimal',
                'default' => '15.00'
            ],
            [
                'name' => 'api_url',
                'title' => 'API URL',
                'type' => 'text',
                'validation' => 'required|url',
                'default' => 'https://staging-express.delhivery.com'
            ],
            [
                'name' => 'api_token',
                'title' => 'API Token',
                'type' => 'password',
                'validation' => 'required'
            ],
            [
                'name' => 'client_name',
                'title' => 'Client Name',
                'type' => 'text',
                'validation' => 'required',
                'default' => 'testclient'
            ],
            [
                'name' => 'warehouse_pincode',
                'title' => 'Warehouse Pincode',
                'type' => 'text',
                'validation' => 'required|digits:6',
                'default' => '110092'
            ]
        ]
    ]
];