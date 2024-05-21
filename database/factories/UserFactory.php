<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nif' => $this->generateDni(),
            'password' => bcrypt('password123'),
            'roles' => 'empleado',
        ];
    }

    /**
     * Genera un DNI español válido.
     *
     * @return string
     */
    private function generateDni(): string
    {
        $dni = '';
        for ($i = 0; $i < 8; $i++) {
            $dni .= rand(0, 9);
        }
        $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $dni .= $letters[$dni % 23];
        return $dni;
    }
}
