<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render('auth/LoginPage');
    }

    public function loginPost(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();
            return Inertia::location(route('home'));
        }

        throw ValidationException::withMessages(['Неверный логин или пароль.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('auth.login');
    }
}
