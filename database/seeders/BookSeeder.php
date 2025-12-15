<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $book = Book::create([
            'title' => 'Atomic Habits',
            'slug' => Str::slug('Atomic Habits'),
            'isbn' => '978-073-521-129-2',
            'description' => 'Perubahan kecil yang memberikan hasil luar biasa.',
            'cover_image' => 'atomic_habits.jpg',
            'publication_year' => 2018,
            
            'author_id' => $authorJames->id,
            // Kita bisa pakai publisher yang sama kayak Laskar Pelangi kalau mau
            'publisher_id' => $publisher->id, 
            'category_id' => $categorySelfHelp->id,
        ]);
    }
}
