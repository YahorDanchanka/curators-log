<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentEmploymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => 'present|array',
            'data.*.first_semester' => 'nullable',
            'data.*.second_semester' => 'nullable',
            'data.*.student_id' => ['required', 'numeric', Rule::exists(Student::class, 'id')],
        ];
    }
}
