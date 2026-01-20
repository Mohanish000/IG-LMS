<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    // Check if user is admin before any action
    public function __construct()
    {
        \Log::info('BookController initialized',json_decode(json_encode(request()->all()), true));
        $this->middleware(function ($request, $next) {
            if (Auth::user()->email !== 'admin@lms.com') {
                return redirect('/dashboard')->with('error', 'Only admin can manage books!');
            }
            \Log::info('Admin access granted to BookController'.json_encode($next));
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            \Log::info('BookController accessed from '.json_encode($trace));
            return $next($request);
        });
    }

    // Display all books
    public function index()
    {
        $books = Book::active()->get(); 
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

        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|unique:books|max:20',
            'price' => 'required|numeric|min:0',
            'total_quantity' => 'required|numeric|min:0',
        ]);
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->price = $request->price;
        $book->total_quantity = $request->total_quantity;
        $book->issued_count = 0;
        $book->available_count = $request->total_quantity;
        $book->save();

        return redirect()->route('admindashboard')->with('success', 'Book added successfully!');
    }

    // Show single book details
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    // Show form to edit book
    public function edit(Book $book)
    {
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
        ]);

        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->price = $request->price;
        $book->total_quantity = $request->total_quantity;
        // Recalculate available count
        $book->available_count = $request->total_quantity - $book->issued_count;
        $book->save();

        return redirect()->route('admindashboard')->with('success', 'Book updated successfully!');
    }

   
    public function destroy(Book $book)
    {

    //log url from which delete request is coming
    \Log::info('Delete request for book ID ' . $book->id . ' coming from URL: ' . url()->previous());

    $book->update(['status' => -1]); 
    
    return redirect()->route('admindashboard')->with('success', 'Book deleted successfully!');
    }
   
}