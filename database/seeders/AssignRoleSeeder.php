<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class AssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = User::where('email', 'admin@perpus.com')->first();

        $roleAdmin = Role::where('name', 'admin')->first();

        if($admin && $roleAdmin) {
            $admin->roles()->attach($roleAdmin->id, 
        ['model_type' => 'App\Models\User']);
        }
    }
}
