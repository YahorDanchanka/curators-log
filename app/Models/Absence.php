<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'reasonable_count', 'unreasonable_count', 'student_id'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
