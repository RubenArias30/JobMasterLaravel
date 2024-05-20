<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_name' => $this->faker->company,
            'client_telephone' => $this->generatePhoneNumber(),
            'client_nif' => $this->generateNif(),
            'client_email' => $this->faker->unique()->safeEmail,
            'client_street' => $this->faker->streetName,
            'client_city' => $this->faker->city,
            'client_postal_code' => $this->faker->numberBetween(00000, 99999)
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
