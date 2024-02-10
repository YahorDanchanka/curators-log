<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCuratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'surname' => 'required',
            'name' => 'required',
            'patronymic' => 'nullable',
            'login' => ['required', Rule::unique(User::class, 'login')],
            'password' => 'required|min:8',
        ];
    }
}
