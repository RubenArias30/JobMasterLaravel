<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_name' => $this->faker->company,
            'client_telephone' => $this->faker->phoneNumber,
            'client_nif' => $this->faker->unique()->regexify('[0-9]{8}[A-Z]{1}'),
            'client_email' => $this->faker->unique()->safeEmail,
            'client_street' => $this->faker->streetName,
            'client_city' => $this->faker->city,
            'client_postal_code' => $this->faker->numberBetween(00000, 99999)
        ];
    }
}
