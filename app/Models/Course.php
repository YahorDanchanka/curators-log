<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'start_education', 'end_education', 'curator_id', 'group_id'];

    public function curator(): BelongsTo
    {
        return $this->belongsTo(Curator::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function expulsions(): HasMany
    {
        return $this->hasMany(Expulsion::class);
    }

    public function characteristics(): HasMany
    {
        return $this->hasMany(CharacteristicStudent::class);
    }

    public function studentEmployment(): HasMany
    {
        return $this->hasMany(StudentEmployment::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(GroupAchievement::class);
    }

    public function gradeReports(): HasMany
    {
        return $this->hasMany(GradeReport::class);
    }

    public function absences(): Builder
    {
        return Absence::whereBetween('date', [$this->start_education, $this->end_education]);
    }

    protected function groupName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->group->specialty->prefix . '-' . $this->number . $this->group->number
        );
    }
}
