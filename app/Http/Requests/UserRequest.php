<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'login' => ['required', Rule::unique(User::class, 'login')->ignore($user)],
            'password' => ($user ? 'nullable|' : '') . 'min:8',
            'role' => [
                'required',
                $user ? Rule::in('admin', 'curator', 'psychologist') : Rule::in('admin', 'psychologist'),
            ],
        ];
    }
}
