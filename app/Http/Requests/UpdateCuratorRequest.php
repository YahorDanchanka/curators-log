<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCuratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $curator = $this->route('curator');

        return [
            'surname' => 'required',
            'name' => 'required',
            'patronymic' => 'nullable',
            'login' => ['required', Rule::unique(User::class, 'login')->ignore($curator->user)],
            'password' => 'nullable|min:8',
        ];
    }
}
