<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        Course::insert([
            [
                'number' => 1,
                'start_education' => '2020-09-01',
                'end_education' => '2021-07-10',
                'curator_id' => 1,
                'group_id' => 1,
            ],
            [
                'number' => 2,
                'start_education' => '2021-09-01',
                'end_education' => '2022-07-10',
                'curator_id' => 1,
                'group_id' => 1,
            ],
            [
                'number' => 3,
                'start_education' => '2022-09-01',
                'end_education' => '2023-07-10',
                'curator_id' => 1,
                'group_id' => 1,
            ],
            [
                'number' => 4,
                'start_education' => '2023-09-01',
                'end_education' => '2024-07-10',
                'curator_id' => 1,
                'group_id' => 1,
            ],
            [
                'number' => 1,
                'start_education' => '2023-09-01',
                'end_education' => '2024-07-10',
                'curator_id' => 2,
                'group_id' => 2,
            ],
            [
                'number' => 2,
                'start_education' => '2024-09-01',
                'end_education' => '2025-07-10',
                'curator_id' => 2,
                'group_id' => 2,
            ],
            [
                'number' => 3,
                'start_education' => '2025-09-01',
                'end_education' => '2026-07-10',
                'curator_id' => 2,
                'group_id' => 2,
            ],
            [
                'number' => 4,
                'start_education' => '2026-09-01',
                'end_education' => '2027-07-10',
                'curator_id' => 2,
                'group_id' => 2,
            ],
        ]);
    }
}
