<x-borrowing-layout> {{-- Or your specific user reservation layout --}}
    <div class="bg-white rounded-lg shadow p-6 sm:p-8">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Request New Reservation</h3>
            <p class="text-gray-600">Select a book and desired expiration date to submit a reservation request.</p>
        </div>

        <form action="{{ route('user.reservations.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 md:grid-cols-2">
                {{-- Book Selection --}}
                <div>
                    <label for="book_id" class="block text-sm font-medium text-gray-700 mb-1">Book <span class="text-red-600">*</span></label>
                    <select name="book_id" id="book_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white @error('book_id') border-red-500 @enderror">
                        <option value="">Select a Book</option>
                        @foreach ($books as $book)
                            {{-- Show available copies here if desired, similar to borrowing create --}}
                            <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>{{ $book->title }} ({{ $book->available_copies }} available)</option>
                        @endforeach
                    </select>
                    @error('book_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Expires At --}}
                <div class="md:col-span-2">
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Reservation Expires At <span class="text-red-600">*</span></label>
                    <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at') }}" required min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('expires_at') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">The reservation will automatically expire after this date and time if not completed.</p>
                    @error('expires_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Notes --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('user.reservations.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Submit Reservation Request
                </button>
            </div>
        </form>
    </div>
</x-borrowing-layout>
