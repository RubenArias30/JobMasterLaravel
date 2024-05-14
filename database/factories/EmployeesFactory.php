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


        $startDate = '-70 years'; // Fecha mínima (hace 70 años)
        $endDate = '-18 years'; // Fecha máxima (hace 18 años menos que la fecha actual)



        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'date_of_birth' =>$this->faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'telephone' => $this->generatePhoneNumber(),
            'country' => $this->faker->country,
            'photo' => $this->faker->imageUrl(),
            'users_id' => $userId,
            'address_id' => $addressId,
            'company_id' => Company::exists() ? Company::inRandomOrder()->first()->id : null,
        ];
    }

    function generatePhoneNumber() {
        $prefixes = ['6', '7'];
        $prefix = $prefixes[array_rand($prefixes)]; // Selecciona aleatoriamente un prefijo válido (6 o 7)

        // Genera los 8 dígitos restantes del número de teléfono
        $digits = '';
        for ($i = 0; $i < 8; $i++) {
            $digits .= mt_rand(0, 9);
        }

        return $prefix . $digits;
    }


}
