<?php

namespace App\Services;

use App\Enums\CharacteristicId;

class EducationLevelService
{
    public static function getCharacteristicIds(): array
    {
        return [
            CharacteristicId::IDEO_POLITICAL_ID->value,
            CharacteristicId::PATRIOTISM_ID->value,
            CharacteristicId::SELF_AWARENESS_ID->value,
            CharacteristicId::MASTERING_INFORMATION_ID->value,
            CharacteristicId::HEALTHY_LIFESTYLE_ID->value,
            CharacteristicId::LEGAL_CULTURE_ID->value,
            CharacteristicId::COMMUNICATION_CULTURE_ID->value,
            CharacteristicId::ECOLOGICAL_CULTURE_ID->value,
            CharacteristicId::FAMILY_READINESS_ID->value,
            CharacteristicId::EMPLOYEE_SKILL_ID->value,
            CharacteristicId::ATTITUDE_TO_LABOUR_ID->value,
            CharacteristicId::ECONOMIC_CULTURE_ID->value,
            CharacteristicId::ATTITUDE_TO_VALUES_ID->value,
            CharacteristicId::EXTRA_INTEREST_ID->value,
            CharacteristicId::CULTURAL_VALUE_ID->value,
        ];
    }
}
