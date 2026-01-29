<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    // Check if user is admin before any action
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->email !== 'admin@lms.com') {
                return redirect('/dashboard')->with('error', 'Only admin can manage books!');
            }
            return $next($request);
        });
    }

    // Display all books
    public function index()
    {
        // Use 'with' to eager load bookDetail (prevents N+1 query problem)
        $books = Book::active()->with('bookDetail')->get(); 
        return view('books.index', compact('books'));
    }

    // Show form to create new book
    public function create()
    {
        return view('books.create');
    }

    // Store new book in database
    public function store(Request $request)
    {
        // Validate book fields
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|unique:books|max:20',
            'price' => 'required|numeric|min:0',
            'total_quantity' => 'required|numeric|min:0',
            // Validate book detail fields
            'description' => 'nullable|string',
            'publisher' => 'nullable|max:255',
            'publication_year' => 'nullable|digits:4',
            'language' => 'nullable|max:50',
            'pages' => 'nullable|integer|min:1',
        ]);

        // Step 1: Create the book first
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->price = $request->price;
        $book->total_quantity = $request->total_quantity;
        $book->issued_count = 0;
        $book->available_count = $request->total_quantity;
        $book->save();

        // Step 2: Create book details using the relationship
        // This is how you create related data in one-to-one
        $book->bookDetail()->create([
            'description' => $request->description,
            'publisher' => $request->publisher,
            'publication_year' => $request->publication_year,
            'language' => $request->language ?? 'English',
            'pages' => $request->pages,
        ]);

        return redirect()->route('admindashboard')->with('success', 'Book added successfully!');
    }

    // Show single book details
    public function show(Book $book)
    {
        // Load the bookDetail relationship
        $book->load('bookDetail');
        return view('books.show', compact('book'));
    }

    // Show form to edit book
    public function edit(Book $book)
    {
        // Load the bookDetail for editing
        $book->load('bookDetail');
        return view('books.edit', compact('book'));
    }

    // Update book in database
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|max:20|unique:books,isbn,' . $book->id,
            'price' => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:0',
            // Book detail validations
            'description' => 'nullable|string',
            'publisher' => 'nullable|max:255',
            'publication_year' => 'nullable|digits:4',
            'language' => 'nullable|max:50',
            'pages' => 'nullable|integer|min:1',
        ]);

        // Update book
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->price = $request->price;
        $book->total_quantity = $request->total_quantity;
        $book->available_count = $request->total_quantity - $book->issued_count;
        $book->save();

        // Update or create book details
        // updateOrCreate is useful - it updates if exists, creates if not
        $book->bookDetail()->updateOrCreate(
            ['book_id' => $book->id], // Search condition
            [                          // Data to update/create
                'description' => $request->description,
                'publisher' => $request->publisher,
                'publication_year' => $request->publication_year,
                'language' => $request->language ?? 'English',
                'pages' => $request->pages,
            ]
        );

        return redirect()->route('admindashboard')->with('success', 'Book updated successfully!');
    }

    // Delete book
    public function destroy(Book $book)
    {
        // BookDetail will be deleted automatically due to 'cascade' in migration
        $book->update(['status' => -1]); 
        
        return redirect()->route('admindashboard')->with('success', 'Book deleted successfully!');
    }
}