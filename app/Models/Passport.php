<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    use HasFactory;

    protected $fillable = ['series', 'number', 'id_number', 'district_department', 'issue_date'];

    protected function passport(): Attribute
    {
        return Attribute::make(
            get: fn() => "$this->id_number, $this->series$this->number выдано $this->district_department РОВД от " .
                $this->asDate($this->issue_date)->format('d.m.Y')
        );
    }
}
