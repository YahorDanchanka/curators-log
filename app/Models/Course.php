<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'start_education', 'end_education', 'curator_id', 'group_id'];

    public function curator(): BelongsTo
    {
        return $this->belongsTo(Curator::class);
    }
}
