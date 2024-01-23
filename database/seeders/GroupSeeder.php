<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        Group::insert([['number' => 3, 'specialty_id' => 1], ['number' => 2, 'specialty_id' => 1]]);
    }
}
