<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "Admin",
            "username" => "admin",
            "email" => "admin@gmail.com",
            "password" => "Perpus123",
            "role" => User::ROLE_SUPER_ADMIN,
            "permissions" => null,
        ]);
    }
}
