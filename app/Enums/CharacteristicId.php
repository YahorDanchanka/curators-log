<?php

namespace App\Enums;

enum CharacteristicId: int
{
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
}
