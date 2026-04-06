<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Resources\BookResource;

class BookController extends Controller
{
    // GET /api/books
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        return BookResource::collection($query->orderBy('title')->get());
    }

    // GET /api/books/{id}
    public function show(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new BookResource($book)
        ]);
    }

    // POST /api/books
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:200',
            'author'      => 'required|string|max:150',
            'isbn'        => 'required|string|max:20|unique:books,isbn',
            'category'    => 'nullable|string|max:100',
            'publisher'   => 'nullable|string|max:150',
            'year'        => 'nullable|integer|min:1900|max:2100',
            'stock'       => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $book = Book::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan.',
            'data'    => new BookResource($book),
        ], 201);
    }

    // PUT /api/books/{id}
    public function update(Request $request, string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:200',
            'author'      => 'sometimes|string|max:150',
            'isbn'        => 'sometimes|string|max:20|unique:books,isbn,' . $id,
            'category'    => 'nullable|string|max:100',
            'publisher'   => 'nullable|string|max:150',
            'year'        => 'nullable|integer|min:1900|max:2100',
            'stock'       => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $book->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diupdate.',
            'data'    => new BookResource($book),
        ], 200);
    }

    // DELETE /api/books/{id}
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan.'
            ], 404);
        }

        // cek stok
        if ($book->stock > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Buku masih memiliki stok, tidak bisa dihapus.'
            ], 422);
        }

        $title = $book->title;
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => "Buku '{$title}' berhasil dihapus."
        ], 200);
    }
}