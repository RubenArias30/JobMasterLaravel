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
            'nif' => $this->generateNif(),
            'password' => bcrypt('password123'),
            'roles' => 'empleado',
        ];
    }

     /**
     * Genera un DNI o NIE español válido.
     *
     * @return string
     */
    private function generateNif(): string
    {
        $nifTypes = ['DNI', 'NIE'];
        $nifType = $nifTypes[rand(0, 1)];
        if ($nifType === 'DNI') {
            return $this->generateDni();
        } else {
            return $this->generateNie();
        }
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

    /**
     * Genera un NIE español válido.
     *
     * @return string
     */
    private function generateNie(): string
    {
        $nieTypes = ['X', 'Y', 'Z'];
        $nieType = $nieTypes[rand(0, 2)];
        $nieNumber = '';
        for ($i = 0; $i < 7; $i++) {
            $nieNumber .= rand(0, 9);
        }
        $nieLetters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $nie = $nieType . $nieNumber . $nieLetters[$nieNumber % 23];
        return $nie;
    }
}
