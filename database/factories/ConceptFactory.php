<?php

namespace Database\Factories;

use App\Models\Invoices;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Concept>
 */
class ConceptFactory extends Factory
{
    protected static $invoices_id = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $invoices_id = static::$invoices_id++;
        return [
            'concept' => $this->faker->sentence,
            'price' => $this->faker->randomNumber(4),
            'quantity' => $this->faker->randomNumber(2),
            'concept_discount' => $this->faker->randomNumber(2),
            'concept_iva' => 21,
            'concept_irpf' => $this->faker->randomNumber(2),
            'invoices_id' => $invoices_id,
        ];
    }
}
