<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        Address::insert([
            [
                'type' => 'Город',
                'residence' => 'Гомель',
                'street' => 'Речицкая 4',
                'region_id' => 40,
                'district_id' => 44,
            ],
        ]);
    }
}
