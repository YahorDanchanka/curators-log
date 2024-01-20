<?php

namespace App\Http\Requests;

use App\Models\Characteristic;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocioPedagogicalCharacteristicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => 'present|array',
            'data.*.student_id' => ['required', 'numeric', Rule::exists(Student::class, 'id')],
            'data.*.characteristic_id' => ['required', 'numeric', Rule::exists(Characteristic::class, 'id')],
        ];
    }
}
