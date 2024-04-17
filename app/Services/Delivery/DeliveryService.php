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
use Symfony\Component\HttpFoundation\Response;

class DeliveryService
{
    public function __construct(
        private readonly DeliveryRepository $repository,
        private readonly DeliveryLogger $logger,
    ) {
    }

    /**
     * @param  string  $deliveryProviderName
     * @return DeliveryProviderInterface
     * @throws Exception
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
            $this->logger->writeException($e->getMessage(), $e->getCode() ?: Response::HTTP_NOT_IMPLEMENTED);
            throw new Exception(
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
     * @throws Exception
     */
    public function sendPackage(array $inputData, string $chosenDeliveryService, string $deliveryTo): bool
    {
        $defaultStoreAddress = config('delivery.store_address.default');
        $deliveryServiceProvider = $this->getDeliveryProvider($chosenDeliveryService);
        $deliveryEntity = $this->repository->store(array_merge($inputData, [
            'delivery_service_name' => $chosenDeliveryService,
            'delivery_address_from' => $inputData['delivery_address_from'] ?? $defaultStoreAddress,
            'delivery_address_to' => $deliveryTo,
        ]));

        try {
            $response = $deliveryServiceProvider->sendPackage($inputData, [
                'from' => $inputData['delivery_address_from'] ?? $defaultStoreAddress,
                'to' => $deliveryTo,
            ]);
        } catch (Exception $e) {
            $this->logger->writeException($e->getMessage(), $e->getCode());
            throw new Exception($e->getMessage(), $e->getCode() ?: Response::HTTP_SERVICE_UNAVAILABLE);
            // Here you can add a new Log logic for ASAP
        }

        // Here, I expect that I will always get a Response (class) entity.
        // If not there should be a check of the instanceof and use another type of interface.
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
