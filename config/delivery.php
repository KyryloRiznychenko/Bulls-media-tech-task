<?php

use App\Enums\Delivery\DeliveryServiceNameStringEnum;

$storeAddressDefault = env('DELIVERY_STORE_ADDRESS__DEFAULT');

return [
    'store_address' => [
        'default' => $storeAddressDefault ? trim($storeAddressDefault, " \n\r\t\v\x00/") : null,
    ],

    'services' => [
        'test' => [
            'api' => env('TEST_API', false)
        ],
        DeliveryServiceNameStringEnum::NOVAPOSHTA->name => [
            'api' => env('NOVAPOSHTA_API', false)
        ],
    ],
];
