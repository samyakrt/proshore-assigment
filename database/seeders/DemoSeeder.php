<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employee::factory(10)->create();
        Employee::factory(10)->create();
        Employee::factory(10)->create();
        Employee::factory(10)->create();
        Employee::factory(10)->create();
    }
}
