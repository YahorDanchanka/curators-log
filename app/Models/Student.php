<?php

namespace App\Models;

use App\Traits\ModelInitials;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory, ModelInitials;

    protected $fillable = ['surname', 'name', 'patronymic', 'group_id'];

    public function characteristics(): BelongsToMany
    {
        return $this->belongsToMany(Characteristic::class)->withPivot(['course_id']);
    }

    /** Иногородний */
    protected function isNonresident(): Attribute
    {
        return Attribute::make(get: fn() => false);
    }

    /** Проживающий в общежитии */
    protected function isDorm(): Attribute
    {
        return Attribute::make(get: fn() => false);
    }
}
