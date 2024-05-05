<?php

namespace App\Models;

use App\Traits\PolicyAccessors;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialty extends Model
{
    use HasFactory, PolicyAccessors;

    protected $fillable = ['name', 'code', 'prefix'];

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
