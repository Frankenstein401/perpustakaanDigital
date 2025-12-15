<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UserProfileSeeder::class,
            AssignRoleSeeder::class,

            // Seeder untuk bagian buku
            AuthorSeeder::class,
            PublisherSeeder::class,
            CategorySeeder::class,
            ShelfSeeder::class,
            BookSeeder::class,
            BookItemSeeder::class
        ]);
    }
}
