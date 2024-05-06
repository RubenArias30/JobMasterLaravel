<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Company;
use App\Models\User;
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
     *
     *
     */
     // Static variables to keep track of IDs
     protected static $userId = 1;
     protected static $addressId = 1;

    public function definition(): array
    {
        // Incrementing IDs
        $userId = static::$userId++;
        $addressId = static::$addressId++;

        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'telephone' => $this->faker->phoneNumber,
            'country' => $this->faker->country,
            'photo' => $this->faker->imageUrl(),
            'users_id' => $userId,
            'address_id' => $addressId,
            'company_id' => Company::exists() ? Company::inRandomOrder()->first()->id : null,
        ];
    }
}
