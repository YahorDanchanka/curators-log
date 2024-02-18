<?php

namespace App\Models;

use App\Traits\ModelInitials;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory, ModelInitials;

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'sex',
        'birthday',
        'citizenship',
        'home_phone',
        'phone',
        'educational_institution',
        'social_conditions',
        'hobbies',
        'other_details',
        'medical_certificate_date',
        'health',
        'apprenticeship',
        'image_url',
        'address_id',
        'study_address_id',
        'passport_id',
        'group_id',
    ];

    public function characteristics(): BelongsToMany
    {
        return $this->belongsToMany(Characteristic::class)->withPivot(['id', 'course_id']);
    }

    public function expulsion(): HasOne
    {
        return $this->hasOne(Expulsion::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function employments(): HasMany
    {
        return $this->hasMany(StudentEmployment::class);
    }

    public function relatives(): BelongsToMany
    {
        return $this->belongsToMany(Relative::class)->withPivot('type');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function studyAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function passport(): BelongsTo
    {
        return $this->belongsTo(Passport::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(StudentAchievement::class);
    }

    public function asocialBehavior(): HasMany
    {
        return $this->hasMany(AsocialBehavior::class);
    }

    public function expertAdvice(): HasMany
    {
        return $this->hasMany(ExpertAdvice::class);
    }

    public function individualWork(): HasMany
    {
        return $this->hasMany(IndividualWork::class);
    }

    protected function studentIndividualWork(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->individualWork
                ->where('type', 'student')
                ->values()
                ->toArray()
        );
    }

    protected function relativeIndividualWork(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->individualWork
                ->where('type', 'relative')
                ->values()
                ->toArray()
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(get: fn(string|null $value) => $value ? asset("storage/$value") : null);
    }

    protected function father(): Attribute
    {
        return Attribute::make(get: fn() => $this->adult_relatives->where('sex', 'мужской')->first());
    }

    protected function mother(): Attribute
    {
        return Attribute::make(get: fn() => $this->adult_relatives->where('sex', 'женский')->first());
    }

    protected function adultRelatives(): Attribute
    {
        return Attribute::make(get: fn() => $this->relatives->whereNull('birthday')->values());
    }

    protected function minorRelatives(): Attribute
    {
        return Attribute::make(get: fn() => $this->relatives->whereNotNull('birthday')->values());
    }

    protected function age(): Attribute
    {
        return Attribute::make(get: fn() => $this->asDate($this->birthday)->age);
    }

    /** Иногородний */
    protected function isNonresident(): Attribute
    {
        return Attribute::make(get: fn() => false);
    }

    /** Проживающий в общежитии */
    protected function isDorm(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->studyAddress &&
                $this->studyAddress->type === 'Город' &&
                $this->studyAddress->residence === 'Гомель' &&
                $this->studyAddress->street === 'Речицкая 4' &&
                $this->studyAddress->region_id === 40 &&
                $this->studyAddress->district_id === 44
        );
    }
}
