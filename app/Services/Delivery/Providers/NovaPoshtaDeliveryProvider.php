<?php

namespace App\Services\Delivery\Providers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class NovaPoshtaDeliveryProvider implements DeliveryProviderInterface
{
    public function __construct(
        private readonly string $apiUrl
    ) {

    }

    /**
     * Look at the interface's PHPdoc
     *
     * @param  array  $inputData
     * @param  array  $addresses
     * @return Response
     */
    public function sendPackage(array $inputData, array $addresses): Response
    {
        return Http::post("$this->apiUrl/delivery", [
            'customer_name' => $inputData['customer_name'],
            'phone_number' => $inputData['customer_phone_number'],
            'email' => $inputData['customer_email'],
            'sender_address' => $addresses['from'] ?? config('delivery.store_address.default'),
            'delivery_address' => $addresses['to'],
        ]);
    }
}
