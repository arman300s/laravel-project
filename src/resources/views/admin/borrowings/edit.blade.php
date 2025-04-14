<x-borrowing-layout>
    <div class="bg-white rounded-lg shadow p-6 sm:p-8">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Edit Borrowing</h3>
            <p class="text-gray-600">Update the details for borrowing №{{ $borrowing->id }}</p>
        </div>

        <form action="{{ route('admin.borrowings.update', $borrowing) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 md:grid-cols-2">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User <span class="text-red-600">*</span></label>
                    <select name="user_id" id="user_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white @error('user_id') @enderror">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $borrowing->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="book_id" class="block text-sm font-medium text-gray-700 mb-1">Book <span class="text-red-600">*</span></label>
                    <select name="book_id" id="book_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white @error('book_id') @enderror">
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}" {{ old('book_id', $borrowing->book_id) == $book->id ? 'selected' : '' }}>{{ $book->title }}</option>
                        @endforeach
                    </select>
                    @error('book_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="borrowed_at" class="block text-sm font-medium text-gray-700 mb-1">Borrowed At</label>
                    <input type="datetime-local" name="borrowed_at" id="borrowed_at"
                           value="{{ old('borrowed_at', $borrowing->borrowed_at->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('borrowed_at') @enderror">
                    @error('borrowed_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="due_at" class="block text-sm font-medium text-gray-700 mb-1">Due At <span class="text-red-600">*</span></label>
                    <input type="datetime-local" name="due_at" id="due_at"
                           value="{{ old('due_at', $borrowing->due_at->format('Y-m-d\TH:i')) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('due_at') @enderror">
                    @error('due_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white @error('status') @enderror">
                        <option value="pending" {{ old('status', $borrowing->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ old('status', $borrowing->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="returned" {{ old('status', $borrowing->status) == 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="overdue" {{ old('status', $borrowing->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                    @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('description') @enderror">{{ old('description', $borrowing->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="mt-4 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="mt-4 ml-4 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-borrowing-layout>
