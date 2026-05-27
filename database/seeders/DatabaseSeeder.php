<?php

namespace Database\Seeders;

use App\Models\user_account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        user_account::create([
            'name' => 'Admin User',
            'email' => 'admin@moneyflow.test',
            'password' => Hash::make('password'),
        ]);

        user_account::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
