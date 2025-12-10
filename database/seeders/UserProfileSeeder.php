<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = User::where('email', 'admin@perpus.com')->first();

        if($admin) {
            UserProfile::create([
                'user_id' => $admin->id,
                'full_name' => 'Administrator System (Frankenstein)',
                'phone_number' => '082112908080',
                'address' => 'Perpustakaan Technology',
                'gender' => 'male',
                'member_type' => 'Admin_staff',
                'institution_name' => 'LibraTech'
            ]);
        }
    }
}
