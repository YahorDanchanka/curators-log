<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Relative;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupFamilyAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => 'present|array',
            'data.*.note' => 'nullable',
            'data.*.student_id' => ['required', 'numeric', Rule::exists(Student::class, 'id')],
            'data.*.relative_id' => ['nullable', 'numeric', Rule::exists(Relative::class, 'id')],
            'data.*.rows' => 'present|array',
            'data.*.rows.*.date' => 'required_with:data.*.rows|date',
            'data.*.rows.*.value' => 'required_with:data.*.rows|numeric',
            'data.*.rows.*.course_id' => ['required_with:data.*.rows', 'numeric', Rule::exists(Course::class, 'id')],
        ];
    }
}
