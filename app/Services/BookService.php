<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookItem;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Str;

class BookService
{

    public function getAllBooks(array $filters = [])
    {
        $query = Book::with('author', 'publisher', 'category');

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        $books = $query->paginate(15);

        return [
            'success' => true,
            'message' => 'Data buku berhasil diambil',
            'data' => [
                'books' => $books->items(),
                'pagination' => [
                    'current_page' => $books->currentPage(),
                    'last_page' => $books->lastPage(),
                    'per_page' => $books->perPage(),
                    'total' => $books->total()
                ]
            ]
        ];
    }

    public function getBookById(string $id) 
    {
        $book = Book::with(['author', 'publisher', 'category', 'items.shelf'])
            ->find($id);

        if (!$book) {
            return [
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'message' => 'detail buku berhasil di ambil',
            'data' => $book
        ];
    }

    public function createBook(array $data)
    {
        if(!Author::find($data['author_ud'])){
            return [
                'success' => false,
                'message' => 'Author tidak ditemukan'
            ];
        }

        if(!Publisher::find($data['publisher_id'])) {
            return [
                'success' => false,
                'message' => 'Penerbit tidak ditemukan'
            ];
        }

        if(!Category::find($data['category_ud'])){
            return [
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ];
        }

        $data['slug'] = Str::slug($data['title']);
        $book = Book::create($data);
        $book->load('author', 'publisher', 'category');

        return [
            'success' => true,
            'message' => 'Data buku berhasil ditambahkan',
            'data' => $data
        ];
    }

    public function updateBook(string $id, array $data) 
    {
        $book = Book::find('id');

        if(!$book) {
            return [
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ];
        }

        if(isset($data['title']) && $data['title'] !== $book->title){
            $data['slug'] = Str::slug($data['title']);
        }

        $book->update($data);
        $book->load('author', 'publisher', 'category');

        return [
            'success' => true,
            'message' => 'Buku berhasil diperbarui',
            'data' => $book
        ];
    }

    public function deleteBook(string $id) 
    {
        $book = Book::find('id');

        if (!$book) {
            return [
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ];
        }

        $borrowedItems = $book->items()->where('status', 'borrowed')->count();

        if (!$borrowedItems) {
            return [
                'success' => false,
                'message' => 'Tidak bisa dihapus, buku sedang dipinjam'
            ];
        }

        $book->delete();

        return [
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ];
    }

}
