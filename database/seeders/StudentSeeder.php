<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::insert([
            [
                'surname' => 'Данченко',
                'name' => 'Егор',
                'patronymic' => 'Игоревич',
                'birthday' => '2004-09-16',
                'group_id' => 1,
            ],
            [
                'surname' => 'Музычкин',
                'name' => 'Тимофей',
                'patronymic' => 'Алексеевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Иконн',
                'name' => 'Диана',
                'patronymic' => 'Андреевна',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Калиничева',
                'name' => 'Алина',
                'patronymic' => 'Сергеевна',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Алексюк',
                'name' => 'Ульяна',
                'patronymic' => 'Александрона',
                'birthday' => '2004-09-17',
                'group_id' => 1,
            ],
            [
                'surname' => 'Батюк',
                'name' => 'Егор',
                'patronymic' => 'Александрович',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Бельский',
                'name' => 'Василий',
                'patronymic' => 'Васильевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Гаврилов',
                'name' => 'Степан',
                'patronymic' => 'Дмитриевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Грищенко',
                'name' => 'Роман',
                'patronymic' => 'Дмитриевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Даниленко',
                'name' => 'Михаил',
                'patronymic' => 'Юрьевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Жовнер',
                'name' => 'Евгений',
                'patronymic' => 'Сергеевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Жулего',
                'name' => 'Артем',
                'patronymic' => 'Александрович',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Закружный',
                'name' => 'Егор',
                'patronymic' => 'Сергеевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Каршанков',
                'name' => 'Павел',
                'patronymic' => 'Денисович',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Лебенков',
                'name' => 'Денис',
                'patronymic' => 'Николаевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Макеев',
                'name' => 'Дмитрий',
                'patronymic' => 'Витальевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Нестерович',
                'name' => 'Илья',
                'patronymic' => 'Владимирович',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Прищепов',
                'name' => 'Максим',
                'patronymic' => 'Геннадьевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Смирнов',
                'name' => 'Павел',
                'patronymic' => 'Витальевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Титов',
                'name' => 'Егор',
                'patronymic' => 'Витальевич',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Трибой',
                'name' => 'Елена',
                'patronymic' => 'Ильинична',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Тукач',
                'name' => 'Даниил',
                'patronymic' => 'Михайлович',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Черкасов',
                'name' => 'Александр',
                'patronymic' => 'Александрович',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
            [
                'surname' => 'Чернякова',
                'name' => 'Алина',
                'patronymic' => 'Сергеевна',
                'birthday' => $this->getBirthday(),
                'group_id' => 1,
            ],
        ]);
    }

    protected function getBirthday()
    {
        return Carbon::parse(fake()->dateTimeBetween('-20 years', '-19 years'))->format('Y-m-d');
    }
}
