<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // CuratorSeeder::class,
            SpecialtySeeder::class,
            // GroupSeeder::class,
            // CourseSeeder::class,
            AdministrativeDivisionSeeder::class,
            AddressSeeder::class,
            // StudentSeeder::class,
            CharacteristicSeeder::class,
            // ExpulsionSeeder::class,
        ]);
    }
}
