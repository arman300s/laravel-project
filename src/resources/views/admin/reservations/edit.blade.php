<x-reservation-layout>
    <div class="bg-white rounded-lg shadow p-6 sm:p-8">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Edit Reservation</h3>
            <p class="text-gray-600">Update the details for reservation №{{ $reservation->id }}</p>
        </div>
        <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 md:grid-cols-2">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User <span class="text-red-600">*</span></label>
                    <select name="user_id" id="user_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white @error('user_id') border-red-500 @enderror">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $reservation->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="book_id" class="block text-sm font-medium text-gray-700 mb-1">Book <span class="text-red-600">*</span></label>
                    <select name="book_id" id="book_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white @error('book_id') border-red-500 @enderror">
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}" {{ old('book_id', $reservation->book_id) == $book->id ? 'selected' : '' }}>{{ $book->title }}</option>
                        @endforeach
                    </select>
                    @error('book_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="reserved_at" class="block text-sm font-medium text-gray-700 mb-1">Reserved At</label>
                    <input type="datetime-local" name="reserved_at" id="reserved_at"
                           value="{{ old('reserved_at', $reservation->reserved_at->format('Y-m-d\TH:i')) }}" readonly
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition cursor-not-allowed @error('reserved_at') border-red-500 @enderror">
                    @error('reserved_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Expires At <span class="text-red-600">*</span></label>
                    <input type="datetime-local" name="expires_at" id="expires_at"
                           value="{{ old('expires_at', $reservation->expires_at->format('Y-m-d\TH:i')) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('expires_at') border-red-500 @enderror">
                    @error('expires_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', $reservation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ old('status', $reservation->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="canceled" {{ old('status', $reservation->status) == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                    @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('description') border-red-500 @enderror">{{ old('description', $reservation->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.reservations.show', $reservation) }}" class="mt-4 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="mt-4 ml-4 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Update Reservation
                </button>
            </div>
        </form>
    </div>
</x-reservation-layout>
