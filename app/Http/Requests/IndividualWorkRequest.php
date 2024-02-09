<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndividualWorkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'content' => 'required',
            'result' => 'nullable',
            'type' => ['required', Rule::in(['relative', 'student'])],
        ];
    }
}
