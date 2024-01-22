<?php

namespace Database\Seeders;

use App\Models\Expulsion;
use Illuminate\Database\Seeder;

class ExpulsionSeeder extends Seeder
{
    public function run(): void
    {
        Expulsion::insert([
            ['date' => '2021-07-10', 'initiator' => 'student', 'reason' => null, 'student_id' => 2, 'course_id' => 1],
            [
                'date' => '2022-05-10',
                'initiator' => 'college',
                'reason' => 'отсутствие на занятиях в течении долгого периода',
                'student_id' => 3,
                'course_id' => 2,
            ],
            [
                'date' => '2021-07-10',
                'initiator' => 'student',
                'reason' => 'перевод в другое учреждение',
                'student_id' => 4,
                'course_id' => 1,
            ],
        ]);
    }
}
