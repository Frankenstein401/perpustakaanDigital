<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = User::create(
            [
                'username' => 'superadmin',
                'email' => 'admin@perpus.com',
                'password' => Hash::make('password'),
                'verification_status' => 'verified',
            ]
        );
    }
}
