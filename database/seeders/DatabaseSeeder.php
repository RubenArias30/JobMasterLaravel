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
