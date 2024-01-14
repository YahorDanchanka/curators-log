<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelInitials;

class Curator extends Model
{
    use HasFactory, ModelInitials;

    protected $fillable = ['surname', 'name', 'patronymic', 'user_id'];
}
