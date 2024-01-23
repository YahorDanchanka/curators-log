<?php

namespace Database\Seeders;

use App\Models\Curator;
use Illuminate\Database\Seeder;

class CuratorSeeder extends Seeder
{
    public function run(): void
    {
        Curator::insert([
            ['surname' => 'Куделич', 'name' => 'Виктор', 'patronymic' => 'Фёдорович', 'user_id' => 2],
            ['surname' => 'Серикова', 'name' => 'Светлана', 'patronymic' => 'Анатольевна', 'user_id' => 3],
        ]);
    }
}
