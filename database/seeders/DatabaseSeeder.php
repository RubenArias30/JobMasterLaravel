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

        // // Crea un nuevo registro en la tabla addresses para el administrador
        // $address = Address::create([
        //     'street' => 'Admin Street',
        //     'city' => 'Admin City',
        //     'country' => 'Admin Country',
        // ]);

        // // Crea el usuario administrador
        // $user = User::create([
        //     'nif' => 'admin',
        //     'password' => bcrypt('admin'),
        //     'roles' => 'admin',
        // ]);

        // // Crea el registro de empleado asociado al usuario administrador
        // $employee = Employees::create([
        //     'name' => 'Admin',
        //     'surname' => 'Admin',
        //     'email' => 'admin@example.com',
        //     'date_of_birth' => '2000-01-01',
        //     'gender' => 'male',
        //     'telephone' => '123456789',
        //     'country' => 'Admin Country',
        //     'users_id' => $user->id,
        //     'address_id' => $address->id,
        //     'company_id' => 1,
        // ]);



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
