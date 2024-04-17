<?php

namespace App\Services\Delivery\Providers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * This class is here just for example.
 */
final class UkrPoshtaDeliveryProvider implements DeliveryProviderInterface
{
    public function __construct(private readonly string $apiUrl)
    {
    }

    public function sendPackage(array $inputData, array $addresses): Response
    {
        return Http::post($this->apiUrl);
    }
}
