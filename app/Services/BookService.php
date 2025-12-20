<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\DB;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Str;

class BookService
{

    public function getAllBooks(array $filters = [])
    {
        $query = Book::with('author', 'publisher', 'category');

        if (isset($filters['category']) && $filters['category'] != '') {
            $query->whereHas('category', function ($q) use ($filters) {
                $searchTerm = $filters['category'];
                if (Str::isUuid($searchTerm)) {
                    $q->where('id', $searchTerm);
                } else {
                    $q->where(function ($subQ) use ($searchTerm) {
                        $subQ->where('name', 'like', "{$searchTerm}%")
                            ->orWhere('slug', 'like', "{$searchTerm}%");
                    });
                }
            });
        }

        if (isset($filters['search']) && $filters['search'] != '') {
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
        DB::beginTransaction();

        try {
            if (isset($data['author_name'])) {
                $author = Author::firstOrCreate(
                    ['name' => trim($data['author_name'])]
                );
                $data['author_id'] = $author->id;
                unset($data['author_name']);
            } elseif (!isset($data['author_id'])) {
                return [
                    'success' => false,
                    'message' => 'Author wajib diisi'
                ];
            }

            if (isset($data['publisher_name'])) {
                $publisher = Publisher::firstOrCreate(
                    ['name' => trim($data['publisher_name'])]
                );
                $data['publisher_id'] = $publisher->id;
                unset($data['publisher_name']);
            } elseif (!isset($data['publisher_id'])) {
                return [
                    'success' => false,
                    'message' => 'Publisher wajib diisi'
                ];
            }

            if ($data['category_slug']) {
                $category = Category::where('slug', $data['category_slug'])->first();

                if (!$category) {
                    return [
                        'success' => false,
                        'message' => 'Kategori tidak ditemukan.'
                    ];
                }
                $data['category_id'] = $category->id;
                unset($data['category_slug']);
            } elseif (!isset($data['category_id'])) {
                return [
                    'success' => false,
                    'message' => 'Category wajib diisi'
                ];
            }

            $data['slug'] = Str::slug($data['title']);

            $book = Book::create($data);
            $book->load(['author', 'publisher', 'category']);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => $book
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Gagal menambahkan buku' . $e->getMessage()
            ];
        }
    }

    public function updateBook(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $book = Book::find($id);

            if (!$book) {
                return [
                    'success' => false,
                    'message' => 'Buku tidak ditemukan'
                ];
            }

            if (isset($data['author_name'])) {
                $author = Author::firstOrCreate(['name' => trim($data['author_name'])]);
                $data['author_id'] = $author->id;
                unset($data['author_name']);
            }

            if (isset($data['publisher_name'])) {
                $publisher = Publisher::firstOrCreate(['name' => trim($data['publisher_name'])]);
                $data['publisher_id'] = $publisher->id;
                unset($data['publisher_name']);
            }

            if (isset($data['category_slug'])) {
                $category = Category::where('slug', $data['category_slug']->first());

                if ($category) {
                    $data['category_id'] = $category->id;
                }

                unset($data['category_slug']);
            }

            if (isset($data['title']) && $data['title'] !== $book->title) {
                $data['slug'] = Str::slug($data['title']);
            }

            $book->update($data);
            $book->load('author', 'publisher', 'category');

            DB::commit();

            return [
                'success' => true,
                'message' => 'Buku berhasil diperbarui',
                'data' => $book
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Buku gagal diupdate' . $e->getMessage()
            ];
        }
    }


    public function deleteBook(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return [
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ];
        }

        $borrowedItems = $book->items()->where('status', 'borrowed')->count();

        if ($borrowedItems > 0) {
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
