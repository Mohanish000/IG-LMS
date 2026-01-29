<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Book Title Header --}}
                    <div class="border-b pb-4 mb-6">
                        <h1 class="text-3xl font-bold text-gray-800">{{ $book->title }}</h1>
                        <p class="text-lg text-gray-600">by {{ $book->author }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        {{-- LEFT COLUMN: Book Table Data --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-blue-600">📚 Book Information</h3>
                            <table class="w-full text-sm">
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-600">ISBN</td>
                                    <td class="py-2">{{ $book->isbn }}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-600">Price</td>
                                    <td class="py-2">₹{{ number_format($book->price, 2) }}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-600">Total Quantity</td>
                                    <td class="py-2">{{ $book->total_quantity }}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-600">Issued</td>
                                    <td class="py-2">
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">
                                            {{ $book->issued_count }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-medium text-gray-600">Available</td>
                                    <td class="py-2">
                                        <span class="px-2 py-1 {{ $book->available_count > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded">
                                            {{ $book->available_count }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        {{-- RIGHT COLUMN: BookDetail Table Data (One-to-One) --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-green-600">📖 Additional Details</h3>
                            <p class="text-xs text-gray-500 mb-3">(From book_details table - One-to-One relationship)</p>
                            
                            @if($book->bookDetail)
                                <table class="w-full text-sm">
                                    <tr class="border-b">
                                        <td class="py-2 font-medium text-gray-600">Publisher</td>
                                        <td class="py-2">{{ $book->bookDetail->publisher ?? 'N/A' }}</td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 font-medium text-gray-600">Publication Year</td>
                                        <td class="py-2">{{ $book->bookDetail->publication_year ?? 'N/A' }}</td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 font-medium text-gray-600">Language</td>
                                        <td class="py-2">{{ $book->bookDetail->language ?? 'N/A' }}</td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 font-medium text-gray-600">Pages</td>
                                        <td class="py-2">{{ $book->bookDetail->pages ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            @else
                                <p class="text-gray-500 italic">No additional details available.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Description Section --}}
                    @if($book->bookDetail && $book->bookDetail->description)
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="text-lg font-semibold mb-2 text-gray-700">📝 Description</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $book->bookDetail->description }}</p>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="mt-8 pt-6 border-t flex justify-between">
                        <a href="{{ route('admindashboard') }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            ← Back to Dashboard
                        </a>
                        
                        @if(Auth::user()->email === 'admin@lms.com')
                            <div class="space-x-2">
                                <a href="{{ route('books.edit', $book->id) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Edit Book
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>