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
            'name' => $this->faker->company,
            'telephone' => $this->faker->phoneNumber,
            'nif' => $this->faker->unique()->regexify('[0-9]{8}[A-Z]{1}'),
            'email' => $this->faker->unique()->safeEmail,
            'address_id' => Address::inRandomOrder()->first()->id,
        ];
    }
}
