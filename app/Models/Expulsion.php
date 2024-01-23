<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expulsion extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'initiator', 'reason', 'student_id'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
