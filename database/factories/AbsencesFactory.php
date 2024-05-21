<?php

namespace Database\Factories;

use App\Models\Employees;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absences>
 */
class AbsencesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'motive' => $this->faker->sentence(),
            'type_absence' => $this->faker->randomElement(['vacation','sick_leave','maternity/paternity','compensatory','leave','others']), // Add 'type' here
            'employees_id' => Employees::inRandomOrder()->first()->id,
        ];
    }

}
