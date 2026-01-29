<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    

    <div class="p-6">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

       <<div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800 bg-white">Book Management</h1>
            <button onclick="openAddModal()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add Book
            </button>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Title</th>
                        <th class="px-4 py-2 border">Author</th>
                        <th class="px-4 py-2 border">ISBN</th>
                        <th class="px-4 py-2 border">Price</th>
                        <th class="px-4 py-2 border">Total Qty</th>
                        <th class="px-4 py-2 border">Issued</th>
                        <th class="px-4 py-2 border">Available</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border text-center">{{ $book->id }}</td>
                            <td class="px-4 py-2 border">{{ $book->title }}</td>
                            <td class="px-4 py-2 border">{{ $book->author }}</td>
                            <td class="px-4 py-2 border">{{ $book->isbn }}</td>
                            <td class="px-4 py-2 border">${{ number_format($book->price, 2) }}</td>
                            <td class="px-4 py-2 border text-center">{{ $book->total_quantity }}</td>
                            <td class="px-4 py-2 border text-center">{{ $book->issued_count }}</td>
                            <td class="px-4 py-2 border text-center">{{ $book->available_count }}</td>
                            <td class="px-4 py-2 border text-center">
                                <button onclick="openViewModal({{ $book->id }})" 
                                        class="text-green-600 hover:underline mr-2">View</button>
                                <button onclick="openEditModal({{ $book->id }})" 
                                        class="text-blue-600 hover:underline mr-2">Edit</button>
                                <button onclick="deleteBook({{ $book->id }})" 
                                        class="text-red-600 hover:underline">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 border text-center text-gray-500">
                                No books found. Click "Add Book" to add your first book.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Book Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Add New Book</h3>
                <button onclick="closeAddModal()" class="text-gray-600 hover:text-gray-900">✕</button>
            </div>
            <form action="{{ route('books.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Title *</label>
                    <input type="text" name="title" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Author *</label>
                    <input type="text" name="author" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">ISBN *</label>
                    <input type="text" name="isbn" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Price ($) *</label>
                    <input type="number" step="0.01" name="price" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Total Quantity *</label>
                    <input type="number" name="total_quantity" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeAddModal()" 
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Book</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Book Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Edit Book</h3>
                <button onclick="closeEditModal()" class="text-gray-600 hover:text-gray-900">✕</button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Title *</label>
                    <input type="text" id="edit_title" name="title" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Author *</label>
                    <input type="text" id="edit_author" name="author" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">ISBN *</label>
                    <input type="text" id="edit_isbn" name="isbn" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Price ($) *</label>
                    <input type="number" step="0.01" id="edit_price" name="price" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Total Quantity *</label>
                    <input type="number" id="edit_total_quantity" name="total_quantity" required 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Book</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Book Modal -->
    <div id="viewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Book Details</h3>
                <button onclick="closeViewModal()" class="text-gray-600 hover:text-gray-900">✕</button>
            </div>
            <div id="viewContent" class="space-y-3">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeViewModal()" 
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        const books = @json($books);

        // Add Modal Functions
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        // Edit Modal Functions
        function openEditModal(bookId) {
            const book = books.find(b => b.id === bookId);
            if (book) {
                document.getElementById('edit_title').value = book.title;
                document.getElementById('edit_author').value = book.author;
                document.getElementById('edit_isbn').value = book.isbn;
                document.getElementById('edit_price').value = book.price;
                document.getElementById('edit_total_quantity').value = book.total_quantity;
                
                document.getElementById('editForm').action = `/books/${bookId}`;
                document.getElementById('editModal').classList.remove('hidden');
            }
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // View Modal Functions
        function openViewModal(bookId) {
            const book = books.find(b => b.id === bookId);
            if (book) {
                const content = `
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">ID</p>
                        <p class="font-semibold">${book.id}</p>
                    </div>
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">Title</p>
                        <p class="font-semibold">${book.title}</p>
                    </div>
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">Author</p>
                        <p class="font-semibold">${book.author}</p>
                    </div>
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">ISBN</p>
                        <p class="font-semibold">${book.isbn}</p>
                    </div>
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">Price</p>
                        <p class="font-semibold">$${parseFloat(book.price).toFixed(2)}</p>
                    </div>
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">Total Quantity</p>
                        <p class="font-semibold">${book.total_quantity}</p>
                    </div>
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">Issued Count</p>
                        <p class="font-semibold text-red-600">${book.issued_count}</p>
                    </div>
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">Available Count</p>
                        <p class="font-semibold text-green-600">${book.available_count}</p>
                    </div>
                `;
                document.getElementById('viewContent').innerHTML = content;
                document.getElementById('viewModal').classList.remove('hidden');
            }
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        // Delete Function
        function deleteBook(bookId) {
            if (confirm('Are you sure you want to delete this book?')) {
                const form = document.getElementById('deleteForm');
                form.action = `/books/${bookId}`;
                form.submit();
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            const viewModal = document.getElementById('viewModal');
            
            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
            if (event.target == viewModal) {
                closeViewModal();
            }
        }
    </script>
</x-app-layout>