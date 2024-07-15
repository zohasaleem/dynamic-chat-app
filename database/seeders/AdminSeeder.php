<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$12$uRaSAipwwqXtmk0oDqwjIu1G3h8MlHoQIV7/O997/Qsl8LlM2LSEq', //12345678
        ]);

        $user->assignRole('admin','support');
    }
}
