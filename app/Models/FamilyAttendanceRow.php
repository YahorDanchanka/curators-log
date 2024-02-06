<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyAttendanceRow extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'value', 'family_attendance_id', 'course_id'];

    public function familyAttendance(): BelongsTo
    {
        return $this->belongsTo(FamilyAttendance::class);
    }
}
