<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = [ 
            [
            'id' => Str::uuid(),
            'name' => 'admin',
            'description' => 'Admin Satu Gani',
            'guard_name' => 'api'
            ],
            [
            'id' => Str::uuid(),
            'name' => 'librarian',
            'description' => 'Pustakawan Satu Roji',
            'guard_name' => 'api'
            ],
            [
            'id' => Str::uuid(),
            'name' => 'member',
            'description' => 'Member satu Rakha',
            'guard_name' => 'api'
            ],
        ];

        foreach($roles as $role) {
            Role::create($role);
        }
    }
}
