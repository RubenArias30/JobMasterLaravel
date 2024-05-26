<?php

namespace Database\Factories;
use Illuminate\Support\Str;
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
            'type_documents' => $this->faker->randomElement(['contract', 'nif', 'curriculum', 'laboral_life', 'payroll', 'proof','others']),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'date' => $this->faker->date(),
            //  'route' => $this->faker->url,
            'route' => 'documents/' . Str::random(10) . '.pdf',
            // 'route' => $this->faker->filePath(),
            'employees_id' => Employees::inRandomOrder()->first()->id,
        ];
    }
}
