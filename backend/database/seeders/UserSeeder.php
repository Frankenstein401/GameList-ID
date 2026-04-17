<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "adminGameList-ID",
            "username" => "admin",
            "email" => "admin@example.com",
            "password" => "password123",
            "role" => "admin"
        ]);
    }
}
