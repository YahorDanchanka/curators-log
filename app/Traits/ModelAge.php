<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait ModelAge
{
    protected function age(): Attribute
    {
        return Attribute::make(get: fn() => $this->asDate($this->birthday)->age);
    }
}
