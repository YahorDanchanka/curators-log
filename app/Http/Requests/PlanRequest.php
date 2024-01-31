<?php

namespace App\Http\Requests;

use App\Enums\PlanSectionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => 'present|array',
            'data.*.start_date' => 'required|date',
            'data.*.end_date' => 'nullable|date',
            'data.*.content' => 'required',
            'data.*.done' => 'required|boolean',
            'data.*.section' => [
                'required',
                Rule::in(array_map(fn(PlanSectionEnum $enum) => $enum->value, PlanSectionEnum::cases())),
            ],
        ];
    }
}
