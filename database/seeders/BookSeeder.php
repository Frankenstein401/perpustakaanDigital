<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Support\Str;
use App\Models\Publisher;
use App\Models\Category;


class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = Author::pluck('id')->toArray(); // gunakan function pluck untuk mengambil data relasi
        $publishers = Publisher::pluck('id')->toArray();
        $categories = Category::pluck('id')->toArray();

        $books = [
            'Laskar Pelangi',
            'Bumi Manusia',
            'Ayat-Ayat Cinta',
            'Negeri 5 Menara',
            'Perahu Kertas',
            'Dilan 1990',
            'Cantik Itu Luka',
            'Pulang',                 
            'Hujan',                 
            'Aroma Karsa',           
            'Supernova: Ksatria, Puteri, dan Bintang Jatuh',
            'Filosofi Teras',         
            'Laut Bercerita',         
            'Orang-Orang Biasa',     
            '5 cm',
            'Ronggeng Dukuh Paruk',  
            'Tenggelamnya Kapal Van der Wijck',
            'Sebuah Seni untuk Bersikap Bodo Amat', 
            'Harry Potter dan Batu Bertuah',
            'Sherlock Holmes: Penelusuran Benang Merah',
        ];

        foreach ($books as $title) {
            Book::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'isbn' => 'ISBN-' . rand(100000, 999999),
                'description' => 'Deskripsi singkat untuk buku' . $title,
                'author_id' => $authors[array_rand($authors)],
                'publisher_id' => $publishers[array_rand($publishers)],
                'category_id' => $categories[array_rand($categories)],
                'publication_year' => rand(2000, 2025),
            ]);
        }
    }
}
