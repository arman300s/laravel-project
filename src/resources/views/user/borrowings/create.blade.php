<x-borrowing-layout>
    <div class="bg-white rounded-lg shadow p-6 sm:p-8">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Request New Borrowing</h3>
            <p class="text-gray-600">Fill in the details below to request a book borrowing.</p>
        </div>

        <form action="{{ route('user.borrowings.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="book_id" class="block text-sm font-medium text-gray-700 mb-1">Book <span class="text-red-600">*</span></label>
                    <select name="book_id" id="book_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white @error('book_id') @enderror">
                        <option value="">Select a Book</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>{{ $book->title }} ({{ $book->available_copies }} available)</option>
                        @endforeach
                    </select>
                    @error('book_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="due_at" class="block text-sm font-medium text-gray-700 mb-1">Due Date <span class="text-red-600">*</span></label>
                    <input type="date" name="due_at" id="due_at" value="{{ old('due_at') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('due_at') @enderror">
                    @error('due_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('description') @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('user.borrowings.index') }}" class="mt-4 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="mt-4 ml-4 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Submit
                </button>
            </div>
        </form>
    </div>
</x-borrowing-layout>
