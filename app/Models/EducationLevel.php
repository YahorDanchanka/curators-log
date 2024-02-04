<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationLevel extends Model
{
    use HasFactory;

    protected $fillable = ['level', 'characteristic_student_id'];

    public function characteristicStudent(): BelongsTo
    {
        return $this->belongsTo(CharacteristicStudent::class);
    }
}
