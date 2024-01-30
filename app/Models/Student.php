<?php

namespace App\Models;

use App\Traits\ModelInitials;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory, ModelInitials;

    protected $fillable = ['surname', 'name', 'patronymic', 'group_id'];

    public function characteristics(): BelongsToMany
    {
        return $this->belongsToMany(Characteristic::class)->withPivot(['id', 'course_id']);
    }

    public function expulsion(): HasOne
    {
        return $this->hasOne(Expulsion::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function employments(): HasMany
    {
        return $this->hasMany(StudentEmployment::class);
    }

    public function relatives(): BelongsToMany
    {
        return $this->belongsToMany(Relative::class)->withPivot('type');
    }

    protected function adultRelatives(): Attribute
    {
        return Attribute::make(get: fn() => $this->relatives->whereNull('birthday')->values());
    }

    protected function minorRelatives(): Attribute
    {
        return Attribute::make(get: fn() => $this->relatives->whereNotNull('birthday')->values());
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
