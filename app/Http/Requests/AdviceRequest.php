<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'comments' => 'nullable',
            'suggestions' => 'nullable',
            'full_name' => 'nullable',
            'position' => 'nullable',
        ];
    }
}
