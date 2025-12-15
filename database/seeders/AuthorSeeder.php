<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $authors = [
            'Tere Liye',
            'Andrea Hirata',
            'Dee Lestari',
            'Pramoedya Ananta Toer',
            'Habiburrahman El Shirazy',
            'Raditya Dika',
            'Fiersa Besari',
            'Boy Candra',
            'Eka Kurniawan',
            'Ayu Utami',
        ];

        foreach($authors as $author){
            Author::create(['name' => $author]);
        }
    }
}
