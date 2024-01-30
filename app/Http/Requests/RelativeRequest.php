<?php

namespace App\Http\Requests;

use App\Models\AdministrativeDivision;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RelativeRequest extends FormRequest
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
            'sex' => Rule::in(['мужской', 'женский']),
            'birthday' => 'nullable|date',
            'job' => 'nullable',
            'position' => 'nullable',
            'phone' => 'nullable',
            'educational_institution' => 'nullable',
            'address' => 'nullable|array',
            'address.type' => 'required_with:address',
            'address.residence' => 'required_with:address',
            'address.street' => 'required_with:address',
            'address.region_id' => ['required_with:address', Rule::exists(AdministrativeDivision::class, 'id')],
            'address.district_id' => ['required_with:address', Rule::exists(AdministrativeDivision::class, 'id')],
            'type' => 'nullable',
        ];
    }
}
