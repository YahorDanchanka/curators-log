<?php

namespace App\Models;

use App\Traits\ModelAge;
use App\Traits\ModelInitials;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relative extends Model
{
    use HasFactory, ModelInitials, ModelAge;

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'sex',
        'birthday',
        'job',
        'position',
        'phone',
        'educational_institution',
        'address_id',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
