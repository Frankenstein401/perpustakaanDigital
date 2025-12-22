<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class BookController extends Controller
{
    //
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'category_id', 'search']);

        $result = $this->bookService->getAllBooks($filters);

        return response()->json($result, 200);
    }

    public function show(String $id)
    {
        $result = $this->bookService->getBookById($id);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => [
                'nullable',
                'string',
                'unique:books,isbn',
                'regex:/^(978|979)-\d{1,5}-\d{1,7}-\d{1,7}-\d{1}$/', 
            ],

            'author_id' => 'required|uuid|exists:author,id',
            'author_name' => 'require|string',

            'publisher_id' => 'required|uuid|exists:publisher,id',
            'publisher_name' => 'required|string',

            'category_id' => 'required|uuid|exists:category,id',
            'category_slug' => 'required|string|exists:category,slug',

            'publisher_year' => 'nullable|integer|min:1900|max' . date('Y'),
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string'
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'isbn.unique' => 'ISBN sudah digunakan buku lain.',
        ]);

        $result = $this->bookService->createBook($request->all());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function update(Request $request, String $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|unique:books,isbn',
            'author_id' => 'sometimes|uuid|exists:author,id',
            'publisher_id' => 'sometimes|uuid|exists:publisher,id',
            'category_id' => 'sometimes|uuid|exists:category,id',
            'publisher_year' => 'sometimes|integer|min:1900|max' . date('Y'),
            'description' => 'sometimes|string',
            'cover_image' => 'sometimes|string'
        ]);

        $result = $this->bookService->updateBook($id, $request->all());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function destroy(String $id)
    {
        $result = $this->bookService->deleteBook($id);

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
