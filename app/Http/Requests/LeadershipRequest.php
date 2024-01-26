<?php

namespace App\Http\Requests;

use App\Models\Characteristic;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leader_id' => ['nullable', 'numeric', Rule::exists(Student::class, 'id')],
            'deputy_leader_id' => ['nullable', 'numeric', Rule::exists(Student::class, 'id')],
            'brsm_secretary_id' => ['nullable', 'numeric', Rule::exists(Student::class, 'id')],
            'union_organizer_id' => ['nullable', 'numeric', Rule::exists(Student::class, 'id')],
            'group_composition' => 'present|array',
            'group_composition.*.student_id' => ['required', 'numeric', Rule::exists(Student::class, 'id')],
            'group_composition.*.characteristic_id' => [
                'required',
                'numeric',
                Rule::exists(Characteristic::class, 'id'),
            ],
        ];
    }
}
