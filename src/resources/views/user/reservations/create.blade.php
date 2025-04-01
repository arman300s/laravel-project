<x-user.layout>
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">New Book Reservation</h2>

            <form method="POST" action="{{ route('user.reservations.store') }}">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <!-- Book Selection -->
                    <div>
                        <label for="book_id" class="block text-sm font-medium text-gray-700">Book</label>
                        <select id="book_id" name="book_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select Book</option>
                            @foreach($availableBooks as $book)
                                <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} ({{ $book->author }})
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reservation Date -->
                    <div>
                        <label for="reservation_date" class="block text-sm font-medium text-gray-700">Reservation Date</label>
                        <input type="date" id="reservation_date" name="reservation_date"
                               value="{{ old('reservation_date', now()->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('reservation_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Special Requests (Optional)</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('user.reservations.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-700 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150">
                        Submit Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-user.layout>
