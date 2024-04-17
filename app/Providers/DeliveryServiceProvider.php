<?php

namespace App\Providers;

use App\Enums\Delivery\DeliveryServiceNameStringEnum;
use App\Loggers\DeliveryLogger;
use App\Repositories\Delivery\DeliveryRepository;
use App\Services\Delivery\DeliveryService;
use App\Services\Delivery\Providers\DeliveryProviderInterface;
use App\Services\Delivery\Providers\JustinDeliveryProvider;
use App\Services\Delivery\Providers\NovaPoshtaDeliveryProvider;
use App\Services\Delivery\Providers\UkrPoshtaDeliveryProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use JsonException;

class DeliveryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DeliveryProviderInterface::class, NovaPoshtaDeliveryProvider::class);
        $this->app->bind(NovaPoshtaDeliveryProvider::class, fn(Application $app) => new NovaPoshtaDeliveryProvider(
            config(sprintf('delivery.services.%s.api', DeliveryServiceNameStringEnum::NOVAPOSHTA->name))
        ));

        $this->app->bind(DeliveryProviderInterface::class, UkrPoshtaDeliveryProvider::class);
        $this->app->bind(UkrPoshtaDeliveryProvider::class, fn(Application $app) => new UkrPoshtaDeliveryProvider(
            config('delivery.services.test.api')
        ));

        $this->app->bind(DeliveryProviderInterface::class, JustinDeliveryProvider::class);
        $this->app->bind(JustinDeliveryProvider::class, fn(Application $app) => new JustinDeliveryProvider(
            config('delivery.services.test.api')
        ));

        $this->app->bind(DeliveryService::class, fn(Application $app) => new DeliveryService(
            new DeliveryRepository(),
            new DeliveryLogger(),
        ));
    }

    /**
     * Bootstrap services.
     * @throws JsonException
     */
    public function boot(): void
    {
        if (!config('delivery.store_address.default', false)) {
            throw new JsonException(__('delivery.errors.default_address_is_not_setup'));
        }
    }
}
