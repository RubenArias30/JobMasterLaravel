<?php

namespace Database\Factories;

use App\Models\Employees;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Incidents>
 */
class IncidentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'incident_type' => $this->faker->randomElement(['tardanza', 'falta', 'cambio de contraseña', 'accidente laboral']),
            'description' => $this->faker->sentence,
            'date' => $this->faker->date(),
            'employees_id' => Employees::inRandomOrder()->first()->id,
        ];
    }
}
