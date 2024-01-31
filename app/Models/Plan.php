<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'end_date', 'content', 'done', 'section', 'month', 'course_id'];

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->end_date
                ? $this->asDate($this->start_date)->format('Y.m.d') .
                    ' - ' .
                    $this->asDate($this->end_date)->format('Y.m.d')
                : $this->asDate($this->start_date)->format('Y.m.d')
        );
    }
}
