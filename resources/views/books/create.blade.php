<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Book') }}
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

                    <form action="{{ route('books.store') }}" method="POST">
                        @csrf
                        
                        {{-- SECTION 1: Basic Book Information --}}
                        <h3 class="text-lg font-semibold mb-4 text-blue-600">📚 Basic Book Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" value="{{ old('title') }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter book title" required>
                            </div>

                            {{-- Author --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Author <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="author" value="{{ old('author') }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter author name" required>
                            </div>

                            {{-- ISBN --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    ISBN <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="isbn" value="{{ old('isbn') }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., 978-3-16-148410-0" required>
                            </div>

                            {{-- Price --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Price (₹) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter price" required>
                            </div>

                            {{-- Total Quantity --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="total_quantity" value="{{ old('total_quantity') }}" min="0"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter quantity" required>
                            </div>
                        </div>

                        {{-- SECTION 2: Book Details (One-to-One Relationship) --}}
                        <h3 class="text-lg font-semibold mb-4 text-green-600">📖 Book Details (Additional Info)</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            These details are stored in a separate table linked via one-to-one relationship.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            {{-- Publisher --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                                <input type="text" name="publisher" value="{{ old('publisher') }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter publisher name">
                            </div>

                            {{-- Publication Year --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Publication Year</label>
                                <input type="number" name="publication_year" value="{{ old('publication_year') }}" 
                                       min="1800" max="{{ date('Y') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., 2024">
                            </div>

                            {{-- Language --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                                <select name="language" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                                    <option value="Hindi" {{ old('language') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                    <option value="Marathi" {{ old('language') == 'Marathi' ? 'selected' : '' }}>Marathi</option>
                                    <option value="Other" {{ old('language') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            {{-- Pages --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Number of Pages</label>
                                <input type="number" name="pages" value="{{ old('pages') }}" min="1"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter number of pages">
                            </div>
                        </div>

                        {{-- Description (Full Width) --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="4"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Enter book description...">{{ old('description') }}</textarea>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admindashboard') }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Add Book
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>