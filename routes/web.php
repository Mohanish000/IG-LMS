<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Regular User Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Dashboard with Books Data
Route::middleware(['auth', 'verified'])->get('/admindashboard', function () {
    // Check if user is admin
    if (Auth::user()->email !== 'admin@lms.com') {
        return redirect('/dashboard')->with('error', 'Unauthorized access!');
    }
    
    // Get all books for admin dashboard
    $books = Book::where('status', 1)->get();
    
    return view('admindashboard', compact('books'));
})->name('admindashboard');

// Book Management Routes (Admin Only)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('books', BookController::class);
});

Route::get('/api-demo', function () {
    return view('api-demo');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';