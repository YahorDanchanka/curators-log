<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteractionWithParent extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'content', 'result'];
}
