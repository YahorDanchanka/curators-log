<?php

namespace App\Http\Requests;

use App\Models\Expulsion;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpulsionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'initiator' => ['required', Rule::in(['student', 'college'])],
            'reason' => 'nullable',
            'student_id' => [
                'required',
                'numeric',
                Rule::exists(Student::class, 'id'),
                Rule::unique(Expulsion::class, 'student_id')->ignore($this->get('id')),
            ],
        ];
    }
}
