<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'residence', 'street', 'apartment_number', 'region_id', 'district_id'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(AdministrativeDivision::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(AdministrativeDivision::class);
    }

    protected function address(): Attribute
    {
        return Attribute::make(
            get: fn(
                $value,
                $attributes
            ) => "{$this->region->name} область, {$this->district->name} район, {$this->type} {$this->residence}, ул. {$this->street}, кв. {$this->apartment_number}"
        );
    }
}
