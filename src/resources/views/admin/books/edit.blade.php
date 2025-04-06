<x-book-layout>
    <div class="bg-white rounded-lg shadow p-6 sm:p-8">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Edit Book</h3>
            <p class="text-gray-600">Update the details for "{{ $book->title }}"</p>
        </div>

        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 md:grid-cols-2">

                {{-- Title --}}
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Book Title <span class="text-red-600">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('title') @enderror">
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Author --}}
                <div>
                    <label for="author_id" class="block text-sm font-medium text-gray-700 mb-1">Author <span class="text-red-600">*</span></label>
                    <select name="author_id" id="author_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white @error('author_id') @enderror">
                        <option value="">Select an Author</option>
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>{{ $author->getFullNameAttribute() }}</option>
                        @endforeach
                    </select>
                    @error('author_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-600">*</span></label>
                    <select name="category_id" id="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white @error('category_id') @enderror">
                        <option value="">Select a Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- ISBN --}}
                <div>
                    <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN <span class="text-red-600">*</span></label>
                    <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}" required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('isbn') border-red-500 @enderror">
                    @error('isbn') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Published Year --}}
                <div>
                    <label for="published_year" class="block text-sm font-medium text-gray-700 mb-1">Published Year <span class="text-red-600">*</span></label>
                    <input type="number" name="published_year" id="published_year" value="{{ old('published_year', $book->published_year) }}" required min="1900" max="{{ date('Y') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('published_year') @enderror">
                    @error('published_year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Available Copies --}}
                <div>
                    <label for="available_copies" class="block text-sm font-medium text-gray-700 mb-1">Available Copies <span class="text-red-600">*</span></label>
                    <input type="number" name="available_copies" id="available_copies" value="{{ old('available_copies', $book->available_copies) }}" required min="0"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('available_copies') border-red-500 @enderror">
                    @error('available_copies') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Total Copies --}}
                <div>
                    <label for="total_copies" class="block text-sm font-medium text-gray-700 mb-1">Total Copies <span class="text-red-600">*</span></label>
                    <input type="number" name="total_copies" id="total_copies" value="{{ old('total_copies', $book->total_copies) }}" required min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('total_copies') @enderror">
                    @error('total_copies') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('description') @enderror">{{ old('description', $book->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- File Uploads --}}
                <div class="md:col-span-2 pt-4 border-t mt-2">
                    <h4 class="text-md font-medium text-gray-800 mb-3">Upload New Book Files (Optional - replaces existing)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        {{-- PDF --}}
                        <div>
                            <label for="file_pdf" class="block text-sm font-medium text-gray-700 mb-1">PDF File (.pdf)</label>
                            @if ($book->file_pdf)
                                <p class="text-xs text-gray-500 mb-1">Current: <span class="font-mono">{{ basename($book->file_pdf) }}</span></p>
                            @endif
                            <input type="file" name="file_pdf" id="file_pdf" accept=".pdf"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('file_pdf') border border-red-500 rounded-lg px-2 py-1 @enderror">
                            @error('file_pdf') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        {{-- DOCX --}}
                        <div>
                            <label for="file_docx" class="block text-sm font-medium text-gray-700 mb-1">Word File (.docx)</label>
                            @if ($book->file_docx)
                                <p class="text-xs text-gray-500 mb-1">Current: <span class="font-mono">{{ basename($book->file_docx) }}</span></p>
                            @endif
                            <input type="file" name="file_docx" id="file_docx" accept=".docx"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('file_docx') border border-red-500 rounded-lg px-2 py-1 @enderror">
                            @error('file_docx') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        {{-- EPUB --}}
                        <div>
                            <label for="file_epub" class="block text-sm font-medium text-gray-700 mb-1">ePub File (.epub)</label>
                            @if ($book->file_epub)
                                <p class="text-xs text-gray-500 mb-1">Current: <span class="font-mono">{{ basename($book->file_epub) }}</span></p>
                            @endif
                            <input type="file" name="file_epub" id="file_epub" accept=".epub"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 @error('file_epub') border border-red-500 rounded-lg px-2 py-1 @enderror">
                            @error('file_epub') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end space-x-3">

                <a href="{{ route('admin.books.show', $book) }}" class="mt-4 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="mt-4 ml-4 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Update Book
                </button>
            </div>
        </form>
    </div>
</x-book-layout>
