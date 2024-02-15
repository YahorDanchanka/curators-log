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

        User::insert([
            ['login' => env('ADMIN_LOGIN'), 'password' => Hash::make(env('ADMIN_PASSWORD'))],
            // ['login' => 'kudelich', 'password' => '$2y$12$tDqJnHrkE.dSatYKHfwu9uzr381MaFxbOkHTDoPJ7D02mfX.31.5y'],
            // ['login' => 'serikova', 'password' => '$2y$12$tDqJnHrkE.dSatYKHfwu9uzr381MaFxbOkHTDoPJ7D02mfX.31.5y'],
        ]);
    }
}
