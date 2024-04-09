<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Company;
use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employees>
 */
class EmployeesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'telephone' => $this->faker->phoneNumber,
            'country' => $this->faker->country,
            'photo' => $this->faker->imageUrl(),
            'users_id' => Users::inRandomOrder()->first()->id,
            'address_id' => Address::inRandomOrder()->first()->id,
            'company_id' => Company::inRandomOrder()->first()->id,
        ];
    }
}
