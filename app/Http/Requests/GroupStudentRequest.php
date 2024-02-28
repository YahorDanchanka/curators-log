<?php

namespace App\Http\Requests;

use App\Models\Address;
use App\Models\AdministrativeDivision;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'surname' => 'required',
            'name' => 'required',
            'patronymic' => 'nullable',
            'sex' => ['required', Rule::in(['мужской', 'женский'])],
            'birthday' => 'nullable|date',
            'citizenship' => 'nullable',
            'home_phone' => 'nullable',
            'phone' => 'nullable',
            'educational_institution' => 'nullable',
            'social_conditions' => 'nullable',
            'hobbies' => 'nullable',
            'other_details' => 'nullable',
            'medical_certificate_date' => 'nullable|date',
            'health' => 'nullable',
            'apprenticeship' => 'nullable',
            'image' => 'nullable|image|max:1024',

            'address' => 'nullable|array',
            'address.type' => 'required_with:address',
            'address.residence' => 'required_with:address',
            'address.street' => 'required_with:address',
            'address.apartment_number' => 'required_with:address',
            'address.region_id' => ['required_with:address', Rule::exists(AdministrativeDivision::class, 'id')],
            'address.district_id' => ['required_with:address', Rule::exists(AdministrativeDivision::class, 'id')],

            'study_address' => 'nullable|array',
            'study_address.type' => 'required_with:study_address',
            'study_address.residence' => 'required_with:study_address',
            'study_address.street' => 'required_with:study_address',
            'study_address.apartment_number' => 'required_with:study_address',
            'study_address.region_id' => [
                'required_with:study_address',
                Rule::exists(AdministrativeDivision::class, 'id'),
            ],
            'study_address.district_id' => [
                'required_with:study_address',
                Rule::exists(AdministrativeDivision::class, 'id'),
            ],

            'passport' => 'nullable|array',
            'passport.series' => 'required_with:passport',
            'passport.number' => 'required_with:passport',
            'passport.id_number' => 'required_with:passport',
            'passport.district_department' => 'required_with:passport',
            'passport.issue_date' => 'required_with:passport|date',
        ];
    }
}
