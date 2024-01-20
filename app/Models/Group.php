<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'specialty_id'];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class)->orderBy('number');
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class)
            ->orderBy('surname')
            ->orderBy('name')
            ->orderBy('patronymic');
    }

    protected function currentCourse(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->courses->first(
                fn(Course $course) => now()->lessThanOrEqualTo($this->asDateTime($course->end_education))
            )
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->current_course
                ? $this->specialty->prefix . '-' . $this->current_course->number . $this->number
                : null
        );
    }
}
