<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'residence',
        'street',
        'house_number',
        'apartment_number',
        'region_id',
        'district_id',
    ];

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
        $chunks = collect([
            ['name' => 'область', 'value' => $this->region->name],
            ['name' => 'район', 'value' => $this->district->name],
            ['name' => mb_strtolower($this->type), 'value' => $this->residence, 'before' => true],
            ['name' => 'ул.', 'value' => $this->street, 'before' => true],
            ['name' => 'д.', 'value' => $this->house_number, 'before' => true],
            ['name' => 'кв.', 'value' => $this->apartment_number, 'before' => true],
        ]);

        return Attribute::make(
            get: fn($value, $attributes) => $chunks
                ->filter(fn(array $chunk) => !($chunk['value'] === null || $chunk['value'] === ''))
                ->map(
                    fn(array $chunk) => isset($chunk['before']) && $chunk['before']
                        ? $chunk['name'] . ' ' . $chunk['value']
                        : $chunk['value'] . ' ' . $chunk['name']
                )
                ->join(', ')
        );
    }
}
