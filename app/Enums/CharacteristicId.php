<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum CharacteristicId: int
{
    use EnumToArray;

    /** Староста */
    case LEADER_ID = 19;
    /** Заместитель старосты */
    case DEPUTY_LEADER_ID = 20;
    /** Секретарь ОО «БРСМ» учебной группы */
    case BRSM_SECRETARY_ID = 21;
    /** Профсоюзный организатор */
    case UNION_ORGANIZER_ID = 22;
    /** Учебный */
    case EDUCATIONAL_SECTOR_ID = 23;
    /** Информационно-идеологический */
    case INFORMATION_IDEOLOGICAL_SECTOR_ID = 24;
    /** Физкультурно-спортивный */
    case SPORT_SECTOR_ID = 25;
    /** Трудовой */
    case LABOR_SECTOR_ID = 26;
    /** Культурно-массовый */
    case CULTURAL_MASS_SECTOR_ID = 27;
    /** Охраны правопорядка */
    case LAW_SECTOR_ID = 28;
    /** Редакционный */
    case EDITORIAL_SECTOR_ID = 29;
    /** Учащиеся, члены ОО «БРСМ» */
    case BRSM_ID = 30;
    /** Идейная убежденность и общественно-политическая активность */
    case IDEO_POLITICAL_ID = 31;
    /** Гражданственность и патриотизм */
    case PATRIOTISM_ID = 32;
    /** Национальное и поликультурное самосознание */
    case SELF_AWARENESS_ID = 33;
    /** Овладение информационной культурой */
    case MASTERING_INFORMATION_ID = 34;
    /** Культура здорового образа жизни */
    case HEALTHY_LIFESTYLE_ID = 35;
    /** Правовая культура */
    case LEGAL_CULTURE_ID = 36;
    /** Культура общения */
    case COMMUNICATION_CULTURE_ID = 37;
    /** Экологическая культура */
    case ECOLOGICAL_CULTURE_ID = 38;
    /** Культура семейных отношений и готовность к семейной жизни */
    case FAMILY_READINESS_ID = 39;
    /** Профессионально значимые качества работника */
    case EMPLOYEE_SKILL_ID = 40;
    /** Отношение к труду */
    case ATTITUDE_TO_LABOUR_ID = 41;
    /** Экономическая культура */
    case ECONOMIC_CULTURE_ID = 42;
    /** Отношение к ценностям */
    case ATTITUDE_TO_VALUES_ID = 43;
    /** Внеучебные интересы личности */
    case EXTRA_INTEREST_ID = 44;
    /** Культурно бытовые ценности */
    case CULTURAL_VALUE_ID = 45;
    /** Член ученического профкома */
    case GROUP_UNION_MEMBER_ID = 46;
    /** Учащийся имеет детей */
    case STUDENT_HAS_CHILDREN = 47;
}
