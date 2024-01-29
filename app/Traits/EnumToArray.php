<?php

namespace App\Traits;

use App\Enums\CharacteristicId;

trait EnumToArray
{
    public static function array(): array
    {
        return array_map(fn($item) => ['name' => $item->name, 'value' => $item->value], CharacteristicId::cases());
    }
}
