<?php

namespace App\Http\Requests;

use App\Enums\MonthEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => 'present|array',
            'data.*.id' => 'required',
            'data.*.content' => 'required',
            'data.*.hours_per_week' => 'required|numeric',
            'data.*.hours_saturday' => 'nullable|numeric',
            'data.*.month' => [
                'required',
                'numeric',
                Rule::in(array_map(fn(MonthEnum $enum) => $enum->value, MonthEnum::cases())),
            ],
            'data.*.week' => ['required', 'numeric', Rule::in(['1', '2', '3', '4'])],
        ];
    }
}
