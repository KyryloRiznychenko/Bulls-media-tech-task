<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\Delivery\DeliveryServiceNameStringEnum;
use App\Models\Delivery;
use App\Services\Delivery\Providers\NovaPoshtaDeliveryProvider;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_is_api_configs_setup(): void
    {
        $this->assertTrue(
            config('delivery.store_address.default')
            && config('delivery.services.test.api')
            && config(sprintf('delivery.services.%s.api', DeliveryServiceNameStringEnum::NOWAPOSHTA->name))
        );
    }

    public function test_the_api_application_returns_a_successful_response(): void
    {
        $response = $this->get('api/');

        $response->assertStatus(200);
    }

    public function test_creation_of_novaposha_delivery_service_and_sending_a_package(): void
    {
        $deliveryEntity = Delivery::create([]);
        $apiUrl = config(sprintf('delivery.services.%s.api', DeliveryServiceNameStringEnum::NOWAPOSHTA->name));
        $defaultStoreAddress = config('delivery.store_address.default');
        $deliveryProvider = new NovaPoshtaDeliveryProvider($apiUrl, $defaultStoreAddress);

        $this->assertTrue($deliveryProvider instanceof NovaPoshtaDeliveryProvider);
    }

    private function generateDeliveryEntity(): Delivery
    {
        $faker = \Faker\Factory::create();

        return Delivery::create([
            'customer_name' => $faker->name(),
            'customer_phone_number' => $faker->phoneNumber(),
            'customer_email' => $faker->email(),

            'package_width' => $faker->numerify('##.##'),
            'package_height' => $faker->numerify('##.##'),
            'package_length'=>$faker->numerify('##.##'),
            'package_weight' => $faker->numerify('##.##'),

            'delivery_service_name' => $faker->randomKey(DeliveryServiceNameStringEnum::getAvailableServices())
        ]);
    }
}
