<?php

namespace Database\Factories;

use App\Enums\Delivery\DeliveryServiceNameStringEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name(),
            'customer_phone_number' => $this->faker->numerify('+38063#######'),
            'customer_email' => $this->faker->email(),

            'package_width' => $this->faker->numerify('##.##'),
            'package_height' => $this->faker->numerify('##.##'),
            'package_length' => $this->faker->numerify('##.##'),
            'package_weight' => $this->faker->numerify('##.####'),

            'delivery_service_name' => $this->faker->randomKey(array_map(
                fn(DeliveryServiceNameStringEnum $enum) => $enum->name,
                DeliveryServiceNameStringEnum::cases()
            )),
            'delivery_address_from' => config('delivery.store_address.default', $this->faker->address()),
            'delivery_address_to' => $this->faker->address(),
        ];
    }
}
