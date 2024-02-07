<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FamilyAttendance extends Model
{
    use HasFactory;

    protected $fillable = ['note', 'student_id', 'relative_id'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function relative(): BelongsTo
    {
        return $this->belongsTo(Relative::class);
    }

    public function rows(): HasMany
    {
        return $this->hasMany(FamilyAttendanceRow::class);
    }
}
