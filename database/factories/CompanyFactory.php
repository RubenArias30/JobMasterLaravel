<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company,
            'company_telephone' => $this->faker->phoneNumber,
            'company_nif' => $this->faker->unique()->regexify('[0-9]{8}[A-Z]{1}'),
            'company_email' => $this->faker->unique()->safeEmail,
            'address_id' => Address::inRandomOrder()->first()->id,
        ];
    }
}
