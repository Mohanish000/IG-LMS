<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All Books') }}
            </h2>
            <a href="{{ route('books.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                + Add New Book
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($books->count() > 0)
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3">Title</th>
                                    <th class="px-4 py-3">Author</th>
                                    <th class="px-4 py-3">ISBN</th>
                                    <th class="px-4 py-3">Publisher</th>
                                    <th class="px-4 py-3">Available</th>
                                    <th class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($books as $book)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium">{{ $book->title }}</td>
                                        <td class="px-4 py-3">{{ $book->author }}</td>
                                        <td class="px-4 py-3">{{ $book->isbn }}</td>
                                        {{-- Accessing one-to-one relationship data --}}
                                        <td class="px-4 py-3">{{ $book->bookDetail->publisher ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 {{ $book->available_count > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded text-xs">
                                                {{ $book->available_count }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 space-x-2">
                                            <a href="{{ route('books.show', $book->id) }}" class="text-blue-600 hover:underline">View</a>
                                            <a href="{{ route('books.edit', $book->id) }}" class="text-green-600 hover:underline">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-gray-500 py-8">No books found. Add your first book!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>