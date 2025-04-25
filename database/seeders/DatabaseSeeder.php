<?php

namespace Database\Seeders;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Cara buat file nya : php artisan make:seeder NamaSeeder
     * pCara jalankan file seeder : php artisan db:seed
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Agnes',
            'email' => "agnes@gmail.com",
            'type' => 'user',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Poltak Alfredo',
            'email' => "poltak@gmail.com",
            'type' => 'user',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Alfredo',
            'email' => "alfredo@gmail.com",
            'type' => 'user',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'type' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
    }
}