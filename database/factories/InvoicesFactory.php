<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoices>
 */
class InvoicesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'subtotal' => $this->faker->randomNumber(4),
        'invoice_discount' => $this->faker->randomNumber(2),
        'invoice_iva' => 21,
        'invoice_irpf' => $this->faker->randomNumber(2),
        'total' => $this->faker->randomNumber(4),
        'company_id' => Company::inRandomOrder()->first()->id,
        'client_id' => Client::inRandomOrder()->first()->id,
        ];
    }
}
