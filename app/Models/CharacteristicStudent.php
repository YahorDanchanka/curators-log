<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CharacteristicStudent extends Pivot
{
    public $incrementing = true;
    protected $fillable = ['student_id', 'characteristic_id', 'course_id'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function characteristic(): BelongsTo
    {
        return $this->belongsTo(Characteristic::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function educationLevel(): HasOne
    {
        return $this->hasOne(EducationLevel::class);
    }
}
