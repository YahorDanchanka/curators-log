<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EducationLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => 'present|array',
            'data.*.level' => 'required|numeric',
            'data.*.characteristic_student.student_id' => ['required', 'numeric', Rule::exists(Student::class, 'id')],
            'data.*.characteristic_student.characteristic_id' => [
                'required',
                'numeric',
                Rule::exists(Student::class, 'id'),
            ],
        ];
    }
}
