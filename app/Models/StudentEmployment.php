<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEmployment extends Model
{
    use HasFactory;

    protected $table = 'student_employment';
    protected $fillable = ['first_semester', 'second_semester', 'student_id', 'course_id'];
}
