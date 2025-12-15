<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Shelf;
use App\Models\BookItem;

class BookItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::all();
        $shelves = Shelf::pluck('id')->toArray();

        foreach ($books as $book) {
            // Bikin 2-3 copy untuk setiap buku
            $copies = rand(2, 3);

            for ($i = 1; $i <= $copies; $i++) {
                BookItem::create([
                    'book_id' => $book->id,
                    'shelf_id' => $shelves[array_rand($shelves)],
                    'inventory_code' => 'LIB-' . date('Y') . '-' . str_pad($book->id, 5, '0') . '-' . $i,
                    'condition' => 'good',
                    'status' => 'available',
                    'procured_at' => now()->subDays(rand(1, 365)),
                ]);
            }
        }
    }
}
