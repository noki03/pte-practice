<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@gmail.com'],
            [
                'ulid'     => Str::ulid(),
                'name'     => 'Test User',
                'password' => 'welcome123',
                'timezone' => 'UTC',
            ]
        );
    }
}
