<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        Specialty::insert([
            ['name' => 'Программное обеспечение информационных технологий', 'code' => 'ПОИТ', 'prefix' => 'ПО'],
        ]);
    }
}
