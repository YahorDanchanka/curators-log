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
                ? $this->asDate($this->start_date)->format('d.m.Y') .
                    ' - ' .
                    $this->asDate($this->end_date)->format('d.m.Y')
                : $this->asDate($this->start_date)->format('d.m.Y')
        );
    }

    /**
     * Возвращает порядковый номер недели согласно дате составления плана
     *
     * @return Attribute
     */
    protected function week(): Attribute
    {
        return Attribute::make(
            get: function () {
                $day = $this->asDate($this->start_date)->day;

                return match (true) {
                    $day > 21 => 4,
                    $day > 14 => 3,
                    $day > 7 => 2,
                    default => 1,
                };
            }
        );
    }
}
