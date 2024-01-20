<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    protected function groupName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->group->specialty->prefix . '-' . $this->number . $this->group->number
        );
    }
}
