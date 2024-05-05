<?php

namespace App\Models;

use App\Traits\PolicyAccessors;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelInitials;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Curator extends Model
{
    use HasFactory, ModelInitials, PolicyAccessors;

    protected $fillable = ['surname', 'name', 'patronymic', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
