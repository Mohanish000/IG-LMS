<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Book Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $book->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">by {{ $book->author }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Book ID</h4>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $book->id }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">ISBN</h4>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $book->isbn }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Price</h4>
                            <p class="text-lg text-gray-900 dark:text-white">${{ number_format($book->price, 2) }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Total Quantity</h4>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $book->total_quantity }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Books Issued</h4>
                            <p class="text-lg text-red-600 font-semibold">{{ $book->issued_count }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Available Copies</h4>
                            <p class="text-lg text-green-600 font-semibold">{{ $book->available_count }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Added On</h4>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $book->created_at->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Last Updated</h4>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $book->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <a href="{{ route('admindashboard') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                            Back to Dashboard
                        </a>
                        <a href="{{ route('books.edit', $book->id) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                            Edit Book
                        </a>
                        <form action="{{ route('books.destroy', $book->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you damn sure you want to delete this book?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                                Delete Book
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>