<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait ModelInitials
{
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->surname .
                ' ' .
                mb_substr($this->name, 0, 1) .
                '. ' .
                mb_substr($this->patronymic, 0, 1) .
                '.'
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(get: fn() => trim($this->surname . ' ' . $this->name . ' ' . $this->patronymic));
    }
}
