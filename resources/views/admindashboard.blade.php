
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <!-- Success/Error Message Container -->
        <div id="alertMessage" class="hidden px-4 py-3 rounded mb-4"></div>


        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800 bg-white">Book Management</h1>
            <div class="flex gap-2">
                <button onclick="loadBooks()" 
                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    🔄 Refresh
                </button>
                <button onclick="openAddModal()" 
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Add Book
                </button>
            </div>
        </div>

        <!-- API Response Display -->
       <div id="apiResponseBox" class="hidden mb-4">
            <span id="apiStatusBadge" class="px-4 py-2 rounded font-bold text-sm text-white"></span>
        </div>

        <!-- Books Table -->
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
                <tbody id="booksTableBody">
                    <tr>
                        <td colspan="9" class="px-4 py-8 border text-center text-gray-500">
                            Loading books...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Book Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Add New Book (POST /api/books)</h3>
                <button onclick="closeAddModal()" class="text-gray-600 hover:text-gray-900">✕</button>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Title *</label>
                <input type="text" id="add_title" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Author *</label>
                <input type="text" id="add_author" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">ISBN *</label>
                <input type="text" id="add_isbn" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Price ($) *</label>
                <input type="number" step="0.01" id="add_price" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Total Quantity *</label>
                <input type="number" id="add_total_quantity" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddModal()" 
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="button" onclick="createBook()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Book</button>
            </div>
        </div>
    </div>

    <!-- Edit Book Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Edit Book (PUT /api/books/<span id="edit_book_id_display"></span>)</h3>
                <button onclick="closeEditModal()" class="text-gray-600 hover:text-gray-900">✕</button>
            </div>
            <input type="hidden" id="edit_book_id">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Title *</label>
                <input type="text" id="edit_title" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Author *</label>
                <input type="text" id="edit_author" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">ISBN *</label>
                <input type="text" id="edit_isbn" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Price ($) *</label>
                <input type="number" step="0.01" id="edit_price" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Total Quantity *</label>
                <input type="number" id="edit_total_quantity" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="button" onclick="updateBook()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Book</button>
            </div>
        </div>
    </div>

    <!-- View Book Modal -->
    <div id="viewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Book Details (GET /api/books/<span id="view_book_id"></span>)</h3>
                <button onclick="closeViewModal()" class="text-gray-600 hover:text-gray-900">✕</button>
            </div>
            <div id="viewContent" class="space-y-3">
                <!-- Content will be loaded via API -->
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeViewModal()" 
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Store books data
        let booksData = [];

        // Load books on page load
        $(document).ready(function() {
            loadBooks();
        });

        // Show API Response
        function showApiResponse(xhr, data) {
            let isSuccess = xhr.status >= 200 && xhr.status < 300;
            let bgColor = isSuccess ? 'bg-green-600' : 'bg-red-600';
            
            $('#apiStatusBadge')
                .removeClass('bg-green-600 bg-red-600')
                .addClass(bgColor)
                .text(`Status Code: ${xhr.status}`);
            
            $('#apiResponseBox').removeClass('hidden');
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                $('#apiResponseBox').addClass('hidden');
            }, 3000);
        }

        // Show Alert Message
        function showAlert(type, message) {
            let bgClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            $('#alertMessage')
                .removeClass('hidden bg-green-100 border-green-400 text-green-700 bg-red-100 border-red-400 text-red-700')
                .addClass(bgClass + ' border')
                .text(message);
            
            setTimeout(() => {
                $('#alertMessage').addClass('hidden');
            }, 3000);
        }

        // ========== GET ALL BOOKS ==========
        function loadBooks() {
            $('#booksTableBody').html('<tr><td colspan="9" class="px-4 py-8 border text-center text-gray-500">Loading...</td></tr>');
            
            $.ajax({
                url: '/api/books',
                type: 'GET',
                success: function(data, textStatus, xhr) {
                    showApiResponse(xhr, data);
                    booksData = data.data || [];
                    renderBooksTable();
                },
                error: function(xhr) {
                    showApiResponse(xhr, xhr.responseJSON || {status: 'error', message: 'Failed to load books'});
                    $('#booksTableBody').html('<tr><td colspan="9" class="px-4 py-8 border text-center text-red-500">Failed to load books</td></tr>');
                }
            });
        }

        // Render Books Table
        function renderBooksTable() {
            if (booksData.length === 0) {
                $('#booksTableBody').html('<tr><td colspan="9" class="px-4 py-8 border text-center text-gray-500">No books found. Click "Add Book" to add your first book.</td></tr>');
                return;
            }

            let html = '';
            booksData.forEach(book => {
                html += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border text-center">${book.id}</td>
                        <td class="px-4 py-2 border">${book.title}</td>
                        <td class="px-4 py-2 border">${book.author}</td>
                        <td class="px-4 py-2 border">${book.isbn}</td>
                        <td class="px-4 py-2 border">$${parseFloat(book.price).toFixed(2)}</td>
                        <td class="px-4 py-2 border text-center">${book.total_quantity}</td>
                        <td class="px-4 py-2 border text-center">${book.issued_count}</td>
                        <td class="px-4 py-2 border text-center">${book.available_count}</td>
                        <td class="px-4 py-2 border text-center">
                            <button onclick="viewBook(${book.id})" class="text-green-600 hover:underline mr-2">View</button>
                            <button onclick="openEditModal(${book.id})" class="text-blue-600 hover:underline mr-2">Edit</button>
                            <button onclick="deleteBook(${book.id})" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                `;
            });
            $('#booksTableBody').html(html);
        }

        // ========== GET SINGLE BOOK (View) ==========
        function viewBook(bookId) {
            $('#view_book_id').text(bookId);
            $('#viewContent').html('<p class="text-gray-500">Loading...</p>');
            $('#viewModal').removeClass('hidden');

            $.ajax({
                url: '/api/books/' + bookId,
                type: 'GET',
                success: function(data, textStatus, xhr) {
                    let book = data.data;
                    
                    let content = `
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
                    $('#viewContent').html(content);
                },
                error: function(xhr) {
                    $('#viewContent').html('<p class="text-red-500">Book not found</p>');
                }
            });
        }

        // ========== POST CREATE BOOK ==========
        function createBook() {
            let bookData = {
                title: $('#add_title').val(),
                author: $('#add_author').val(),
                isbn: $('#add_isbn').val(),
                price: $('#add_price').val(),
                total_quantity: $('#add_total_quantity').val()
            };

            $.ajax({
                url: '/api/books',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(bookData),
                success: function(data, textStatus, xhr) {
                    showApiResponse(xhr, data);
                    showAlert('success', 'Book created successfully!');
                    closeAddModal();
                    clearAddForm();
                    loadBooks(); // Refresh table
                },
                error: function(xhr) {
                    showApiResponse(xhr, xhr.responseJSON || {status: 'error', message: 'Creation failed'});
                    let errorMsg = xhr.responseJSON?.message || 'Failed to create book';
                    if (xhr.responseJSON?.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).flat().join(', ');
                    }
                    showAlert('error', errorMsg);
                }
            });
        }

        // ========== PUT UPDATE BOOK ==========
        function updateBook() {
            let bookId = $('#edit_book_id').val();
            let bookData = {
                title: $('#edit_title').val(),
                author: $('#edit_author').val(),
                isbn: $('#edit_isbn').val(),
                price: $('#edit_price').val(),
                total_quantity: $('#edit_total_quantity').val()
            };

            $.ajax({
                url: '/api/books/' + bookId,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(bookData),
                success: function(data, textStatus, xhr) {
                    showApiResponse(xhr, data);
                    showAlert('success', 'Book updated successfully!');
                    closeEditModal();
                    loadBooks(); // Refresh table
                },
                error: function(xhr) {
                    showApiResponse(xhr, xhr.responseJSON || {status: 'error', message: 'Update failed'});
                    let errorMsg = xhr.responseJSON?.message || 'Failed to update book';
                    if (xhr.responseJSON?.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).flat().join(', ');
                    }
                    showAlert('error', errorMsg);
                }
            });
        }

        // ========== DELETE BOOK ==========
        function deleteBook(bookId) {
            if (confirm('Are you sure you want to delete book #' + bookId + '?')) {
                $.ajax({
                    url: '/api/books/' + bookId,
                    type: 'DELETE',
                    success: function(data, textStatus, xhr) {
                        showApiResponse(xhr, data);
                        showAlert('success', 'Book deleted successfully!');
                        loadBooks(); // Refresh table
                    },
                    error: function(xhr) {
                        showApiResponse(xhr, xhr.responseJSON || {status: 'error', message: 'Delete failed'});
                        showAlert('error', 'Failed to delete book');
                    }
                });
            }
        }

        // ========== MODAL FUNCTIONS ==========
        function openAddModal() {
            $('#addModal').removeClass('hidden');
        }

        function closeAddModal() {
            $('#addModal').addClass('hidden');
        }

        function clearAddForm() {
            $('#add_title, #add_author, #add_isbn, #add_price, #add_total_quantity').val('');
        }

        function openEditModal(bookId) {
            // First fetch the book data via API
            $.ajax({
                url: '/api/books/' + bookId,
                type: 'GET',
                success: function(data) {
                    let book = data.data;
                    $('#edit_book_id').val(book.id);
                    $('#edit_book_id_display').text(book.id);
                    $('#edit_title').val(book.title);
                    $('#edit_author').val(book.author);
                    $('#edit_isbn').val(book.isbn);
                    $('#edit_price').val(book.price);
                    $('#edit_total_quantity').val(book.total_quantity);
                    $('#editModal').removeClass('hidden');
                },
                error: function(xhr) {
                    showAlert('error', 'Failed to load book data');
                }
            });
        }

        function closeEditModal() {
            $('#editModal').addClass('hidden');
        }

        function closeViewModal() {
            $('#viewModal').addClass('hidden');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.id === 'addModal') closeAddModal();
            if (event.target.id === 'editModal') closeEditModal();
            if (event.target.id === 'viewModal') closeViewModal();
        }
    </script>
</x-app-layout>