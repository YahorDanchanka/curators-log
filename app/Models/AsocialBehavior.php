<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsocialBehavior extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'action', 'sanctions', 'result', 'student_id'];
}
