<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAchievement extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'semester'];
}
