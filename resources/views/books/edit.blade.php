<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Book') }}: {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Display validation errors --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('books.update', $book->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Required for update requests --}}
                        
                        {{-- SECTION 1: Basic Book Information --}}
                        <h3 class="text-lg font-semibold mb-4 text-blue-600">📚 Basic Book Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" value="{{ old('title', $book->title) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            {{-- Author --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Author <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="author" value="{{ old('author', $book->author) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            {{-- ISBN --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    ISBN <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            {{-- Price --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Price (₹) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" value="{{ old('price', $book->price) }}" step="0.01" min="0"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            {{-- Total Quantity --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="total_quantity" value="{{ old('total_quantity', $book->total_quantity) }}" min="0"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            {{-- Display Only: Issued & Available (Read Only) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Currently Issued</label>
                                <input type="text" value="{{ $book->issued_count }}" 
                                       class="w-full bg-gray-100 border-gray-300 rounded-md" readonly>
                            </div>
                        </div>

                        {{-- SECTION 2: Book Details (One-to-One Relationship) --}}
                        <h3 class="text-lg font-semibold mb-4 text-green-600">📖 Book Details (One-to-One)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            {{-- Publisher --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                                <input type="text" name="publisher" 
                                       value="{{ old('publisher', $book->bookDetail->publisher ?? '') }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter publisher name">
                            </div>

                            {{-- Publication Year --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Publication Year</label>
                                <input type="number" name="publication_year" 
                                       value="{{ old('publication_year', $book->bookDetail->publication_year ?? '') }}" 
                                       min="1800" max="{{ date('Y') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Language --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                                <select name="language" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @php $currentLang = old('language', $book->bookDetail->language ?? 'English'); @endphp
                                    <option value="English" {{ $currentLang == 'English' ? 'selected' : '' }}>English</option>
                                    <option value="Hindi" {{ $currentLang == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                    <option value="Marathi" {{ $currentLang == 'Marathi' ? 'selected' : '' }}>Marathi</option>
                                    <option value="Other" {{ $currentLang == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            {{-- Pages --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Number of Pages</label>
                                <input type="number" name="pages" 
                                       value="{{ old('pages', $book->bookDetail->pages ?? '') }}" min="1"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="4"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $book->bookDetail->description ?? '') }}</textarea>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admindashboard') }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Update Book
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>