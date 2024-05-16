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
            'incident_type' => $this->faker->randomElement(['Delay','Absence','password_change','Request','Complaint','Others']),
            'description' => $this->faker->sentence,
            'date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['completed', 'pending']),
            'employees_id' => Employees::inRandomOrder()->first()->id,

        ];
    }
}
