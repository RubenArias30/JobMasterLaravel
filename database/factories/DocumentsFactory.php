<?php

namespace Database\Factories;

use App\Models\Employees;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Documents>
 */
class DocumentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type_documents' => $this->faker->randomElement(['contracts', 'nif', 'curriculum', 'laboral_life', 'payroll', 'proof']),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'date' => $this->faker->date(),
            'route' => $this->faker->url,
            'employees_id' => Employees::inRandomOrder()->first()->id,
        ];
    }
}
