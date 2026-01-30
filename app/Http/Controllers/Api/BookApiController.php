<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookApiController extends Controller
{
    // GET - Get all books
    public function index(): JsonResponse
    {
        $books = Book::active()->get();
        
        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Books retrieved successfully',
            'total' => $books->count(),
            'data' => $books
        ], 200);
    }

    // GET - Get single book
    public function show($id): JsonResponse
    {
        $book = Book::with('bookDetail')->find($id);
        
        if (!$book || $book->status == -1) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Book not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Book retrieved successfully',
            'data' => $book
        ], 200);
    }

    // POST - Create new book
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'price' => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:0',
        ]);

        $validated['issued_count'] = 0;
        $validated['available_count'] = $validated['total_quantity'];
        $validated['status'] = 1;

        $book = Book::create($validated);

        return response()->json([
            'status' => 'success',
            'status_code' => 201,
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }

    // PUT - Update book
    public function update(Request $request, $id): JsonResponse
    {
        $book = Book::find($id);
        
        if (!$book || $book->status == -1) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Book not found',
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $id,
            'price' => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:0',
        ]);

        $validated['available_count'] = $validated['total_quantity'] - $book->issued_count;

        $book->update($validated);

        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Book updated successfully',
            'data' => $book
        ], 200);
    }

    // DELETE - Delete book (soft delete)
    public function destroy($id): JsonResponse
    {
        $book = Book::find($id);
        
        if (!$book || $book->status == -1) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Book not found',
                'data' => null
            ], 404);
        }

        $book->update(['status' => -1]);

        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Book deleted successfully',
            'data' => null
        ], 200);
    }
}