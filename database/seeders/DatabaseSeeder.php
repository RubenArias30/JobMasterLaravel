<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Absences;
use App\Models\Address;
use App\Models\Attendance;
use App\Models\Client;
use App\Models\Company;
use App\Models\Concept;
use App\Models\Documents;
use App\Models\Employees;
use App\Models\Incidents;
use App\Models\Invoices;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use App\Models\User;
use League\CommonMark\Node\Block\Document;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $company = Company::inRandomOrder()->first();
        $companyId = $company ? $company->id : null;

        // Crea un usuario administrador
        $admin = User::factory()->create([
            'nif' => 'admin',
            'password' => bcrypt('admin'),
            'roles' => 'admin',
        ]);

        // Crea una dirección para el usuario administrador
        $address = Address::factory()->create([
            'street' => 'Admin Street',
            'city' => 'Admin City',
            'postal_code' => '08904',
        ]);

        // Crea un empleado asociado al usuario administrador
        $employee = Employees::factory()->create([
            'name' => 'Admin',
            'surname' => 'Admin',
            'email' => 'admin@example.com',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'telephone' => '123456789',
            'country' => 'Admin Country',
            'users_id' => $admin->id,
            'address_id' => $address->id,
            'company_id' => $companyId,
        ]);







        User::factory(10)->create();
        Address::factory(10)->create();
        Company::factory(10)->create();
        Client::factory(10)->create();
        Employees::factory(10)->create();
        Attendance::factory(10)->create();
        Absences::factory(10)->create();
        Documents::factory(10)->create();
        Incidents::factory(10)->create();
        Invoices::factory(10)->create();
        Concept::factory(10)->create();
        Schedule::factory(10)->create();
    }
}
