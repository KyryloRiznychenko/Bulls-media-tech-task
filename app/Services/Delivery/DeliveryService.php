<?php

namespace App\Services\Delivery;

use App\Enums\Delivery\DeliveryServiceNameStringEnum;
use App\Loggers\DeliveryLogger;
use App\Repositories\Delivery\DeliveryRepository;
use App\Services\Delivery\Providers\DeliveryProviderInterface;
use App\Services\Delivery\Providers\JustinDeliveryProvider;
use App\Services\Delivery\Providers\NovaPoshtaDeliveryProvider;
use App\Services\Delivery\Providers\UkrPoshtaDeliveryProvider;
use Exception;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class DeliveryService
{
    public function __construct(
        private readonly string $defaultStoreAddress,
        private readonly DeliveryRepository $repository,
        private readonly DeliveryLogger $logger,
    ) {
    }

    /**
     * @param  string  $deliveryProviderName
     * @return DeliveryProviderInterface
     * @throws JsonException
     */
    private function getDeliveryProvider(string $deliveryProviderName): DeliveryProviderInterface
    {
        try {
            $deliveryProvider = match ($deliveryProviderName) {
                DeliveryServiceNameStringEnum::NOVAPOSHTA->name => app(NovaPoshtaDeliveryProvider::class),
                DeliveryServiceNameStringEnum::UKRPOSHTA->name => app(UkrPoshtaDeliveryProvider::class),
                DeliveryServiceNameStringEnum::JUSTIN->name => app(JustinDeliveryProvider::class),
                // Here you can add a new delivery provider.
                default => throw new Exception()
            };
        } catch (Exception $e) {
            throw new JsonException(
                "{$e->getLine()} \nUnavailable Delivery Service Provider: $deliveryProviderName",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $deliveryProvider;
    }

    /**
     * @param  array{
     *     customer_name:string,
     *     customer_phone_number:string,
     *     customer_email:string,
     *     package_width:integer|numeric,
     *     package_heigth:integer|numeric,
     *     package_length:integer|numeric,
     *     package_weight:integer|numeric,
     *     ...
     * }  $inputData
     * @param  string  $chosenDeliveryService
     * @param  string  $deliveryTo
     * @return bool
     * @throws JsonException
     */
    public function sendPackage(array $inputData, string $chosenDeliveryService, string $deliveryTo): bool
    {
        $deliveryServiceProvider = $this->getDeliveryProvider($chosenDeliveryService);
        $deliveryEntity = $this->repository->store(array_merge($inputData, [
            'delivery_service_name' => $chosenDeliveryService,
            'delivery_address_from' => $inputData['delivery_address_from'] ?? $this->defaultStoreAddress,
            'delivery_address_to' => $deliveryTo,
        ]));

        try {
            $response = $deliveryServiceProvider->sendPackage($inputData, [
                'from' => $inputData['delivery_address_from'] ?? $this->defaultStoreAddress,
                'to' => $deliveryTo,
            ]);
        } catch (Exception $e) {
            $this->logger->writeException($e->getMessage(), $e->getCode());
            throw new JsonException($e->getMessage(), $e->getCode());

            // Here you can add a new Log logic for ASAP
        }

        if (!in_array($response->status(), [200, 201])) {
            // Log via the magic method __invoke.
            $this->logger->writeResponseResult($deliveryEntity->uuid, $response);

            return false;
        } else {
            $this->repository->update($deliveryEntity->uuid, ['is_send_successful' => true]);
        }

        return true;
    }
}
