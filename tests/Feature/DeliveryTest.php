<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\Delivery\DeliveryServiceNameStringEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeliveryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_is_api_configs_setup(): void
    {
        $this->assertTrue(
            config('delivery.store_address.default')
            && config('delivery.services.test.api')
            && config(sprintf('delivery.services.%s.api', DeliveryServiceNameStringEnum::NOVAPOSHTA->name))
        );
    }

    public function test_the_api_application_returns_a_successful_response(): void
    {
        $response = $this->get('api/');

        $response->assertStatus(200);
    }

    public function test_sending_package(): void
    {
        $response = $this->post(
            route('delivery.send'),
            $this->generatePackageRequestData(DeliveryServiceNameStringEnum::UKRPOSHTA->name)
        );

        $response->assertStatus(200);
    }

    private function generatePackageRequestData(
        string $deliveryServiceName = DeliveryServiceNameStringEnum::NOVAPOSHTA->name
    ): array {
        return [
            'customer_name' => $this->faker->name(),
            'customer_phone_number' => $this->faker->numerify('+38063#######'),
            'customer_email' => $this->faker->email(),

            'package_width' => $this->faker->numerify('##.###'),
            'package_height' => $this->faker->numerify('##.##'),
            'package_length' => $this->faker->numerify('##.##'),
            'package_weight' => $this->faker->numerify('##.##'),

            'delivery_address_to' => $this->faker->address(),
            'delivery_service_name' => $deliveryServiceName
        ];
    }
}
