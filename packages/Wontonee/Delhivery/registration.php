<?php
return [
    'name' => 'Delhivery Shipping',
    'slug' => 'delhivery',
    'namespace' => 'Wontonee\\Delhivery',
    'version' => '1.0.0',
    'description' => 'Delhivery Shipping for Bagisto 2.3',
    'authors' => [
        [
            'name' => 'Your Name',
            'email' => 'your@email.com'
        ]
    ],
    'providers' => [
        \Wontonee\Delhivery\Providers\ModuleServiceProvider::class,
        \Wontonee\Delhivery\Providers\DelhiveryServiceProvider::class
    ],
    'dependencies' => [],
    'autoload' => [
        'psr-4' => [
            "Wontonee\\Delhivery\\" => "src/"
        ]

    ]
];