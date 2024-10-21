<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::where('email', 'admin@admin.com')->doesntExist()) {
            User::create([
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin@admin.com'),
                'role' => 'admin',
            ]);
        }
    }
}
