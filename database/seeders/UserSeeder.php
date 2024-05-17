<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $login = env('ADMIN_LOGIN');
        $password = env('ADMIN_PASSWORD');

        if (!$login || !$password) {
            throw new \Exception('Login or password is empty');
        }

        $user = User::create(['login' => env('ADMIN_LOGIN'), 'password' => Hash::make(env('ADMIN_PASSWORD'))]);
        $user->assignRole('admin');
    }
}
