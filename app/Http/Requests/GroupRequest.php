<?php

namespace App\Http\Requests;

use App\Models\Curator;
use App\Models\Specialty;
use App\Validators\ValidateGroupCoursesPeriod;
use App\Validators\ValidateGroupDateOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'number' => 'required|numeric|min:1|max:255',
            'specialty_id' => ['required', 'numeric', Rule::exists(Specialty::class, 'id')],
            'courses' => 'required|array|min:1|max:4',
            'courses.*.number' => 'required|numeric',
            'courses.*.start_education' => 'required|date',
            'courses.*.end_education' => 'required|date|after:start_education',
            'courses.*.curator_id' => ['required', 'numeric', Rule::exists(Curator::class, 'id')],
        ];
    }

    public function after()
    {
        return [new ValidateGroupDateOrder(), new ValidateGroupCoursesPeriod()];
    }
}
