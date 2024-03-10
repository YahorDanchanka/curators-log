<?php

namespace Database\Seeders;

use App\Models\Characteristic;
use Illuminate\Database\Seeder;

class CharacteristicSeeder extends Seeder
{
    public function run(): void
    {
        Characteristic::insert([
            ['name' => 'Дети-сироты* (до 18 лет)', 'type' => 'socio-pedagogical'],
            ['name' => 'Дети, оставшиеся без попечения родителей* (до 18 лет)', 'type' => 'socio-pedagogical'],
            ['name' => 'Лица из числа детей-сирот (18-23 года)', 'type' => 'socio-pedagogical'],
            [
                'name' => 'Лица из числа детей, оставшихся без попечения родителей* (18-23 года)',
                'type' => 'socio-pedagogical',
            ],
            [
                'name' => 'Дети-сироты, дети, оставшиеся без попечения родителей, на семейных формах устройства',
                'type' => 'socio-pedagogical',
            ],
            [
                'name' => 'Лица из числа детей-сирот, детей, оставшихся без попечения родителей, проживающие отдельно',
                'type' => 'socio-pedagogical',
            ],
            [
                'name' =>
                    'Дети-сироты, дети, оставшиеся без попечения родителей, зачисленные на государственное обеспечение в УО',
                'type' => 'socio-pedagogical',
            ],
            [
                'name' =>
                    'Лица из числа детей-сирот, детей, оставшихся без попечения родителей, зачисленные на государственное обеспечение в УО',
                'type' => 'socio-pedagogical',
            ],
            ['name' => 'Учащиеся из многодетных семей', 'type' => 'socio-pedagogical'],
            ['name' => 'Учащиеся из неполных семей', 'type' => 'socio-pedagogical'],
            [
                'name' => 'Учащиеся из регионов, пострадавших от катастрофы на Чернобыльской АЭС',
                'type' => 'socio-pedagogical',
            ],
            ['name' => 'Учащиеся с особенностями психофизического развития', 'type' => 'socio-pedagogical'],
            ['name' => 'Учащиеся, имеющие группу инвалидности', 'type' => 'socio-pedagogical'],
            ['name' => 'Учащиеся, чьи родители являются инвалидами', 'type' => 'socio-pedagogical'],
            [
                'name' => 'Несовершеннолетние, семьи, признанные находящимися в социально опасном положении',
                'type' => 'socio-pedagogical',
            ],
            [
                'name' => 'Несовершеннолетние, признанные нуждающимися в государственной защите',
                'type' => 'socio-pedagogical',
            ],
            ['name' => 'Несовершеннолетние, с которыми проводится ИПР', 'type' => 'socio-pedagogical'],
            [
                'name' => 'Несовершеннолетние, в отношении которых проводится комплексная реабилитация',
                'type' => 'socio-pedagogical',
            ],
            ['name' => 'Староста', 'type' => 'leadership'],
            ['name' => 'Заместитель старосты', 'type' => 'leadership'],
            ['name' => 'Секретарь ОО «БРСМ» учебной группы', 'type' => 'leadership'],
            ['name' => 'Профсоюзный организатор', 'type' => 'leadership'],
            ['name' => 'Учебный', 'type' => 'group-composition'],
            ['name' => 'Информационно-идеологический', 'type' => 'group-composition'],
            ['name' => 'Физкультурно-спортивный', 'type' => 'group-composition'],
            ['name' => 'Трудовой', 'type' => 'group-composition'],
            ['name' => 'Культурно-массовый', 'type' => 'group-composition'],
            ['name' => 'Охраны правопорядка', 'type' => 'group-composition'],
            ['name' => 'Редакционный', 'type' => 'group-composition'],
            ['name' => 'Учащиеся, члены ОО «БРСМ»', 'type' => 'student-employment'],
            ['name' => 'Идейная убежденность и общественно-политическая активность', 'type' => 'education-level'],
            ['name' => 'Гражданственность и патриотизм', 'type' => 'education-level'],
            ['name' => 'Национальное и поликультурное самосознание', 'type' => 'education-level'],
            ['name' => 'Овладение информационной культурой', 'type' => 'education-level'],
            ['name' => 'Культура здорового образа жизни', 'type' => 'education-level'],
            ['name' => 'Правовая культура', 'type' => 'education-level'],
            ['name' => 'Культура общения', 'type' => 'education-level'],
            ['name' => 'Экологическая культура', 'type' => 'education-level'],
            ['name' => 'Культура семейных отношений и готовность к семейной жизни', 'type' => 'education-level'],
            ['name' => 'Профессионально значимые качества работника', 'type' => 'education-level'],
            ['name' => 'Отношение к труду', 'type' => 'education-level'],
            ['name' => 'Экономическая культура', 'type' => 'education-level'],
            ['name' => 'Отношение к ценностям', 'type' => 'education-level'],
            ['name' => 'Внеучебные интересы личности', 'type' => 'education-level'],
            ['name' => 'Культурно бытовые ценности', 'type' => 'education-level'],
            ['name' => 'Член ученического профкома', 'type' => 'student-employment'],
            ['name' => 'Учащийся имеет детей', 'type' => 'other-characteristic'],
        ]);
    }
}
