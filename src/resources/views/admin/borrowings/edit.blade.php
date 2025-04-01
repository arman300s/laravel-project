<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Borrowing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Edit Borrowing</h1>

                    @if(session('success'))
                        <div class="alert alert-success mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.borrowings.update', $borrowing->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- User -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User</label>
                            <select id="user_id" name="user_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $borrowing->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Book -->
                        <div>
                            <label for="book_id" class="block text-sm font-medium text-gray-700 mb-2">Book</label>
                            <select id="book_id" name="book_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}" {{ $borrowing->book_id == $book->id ? 'selected' : '' }}>
                                        {{ $book->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Borrowed Date -->
                        <div>
                            <label for="borrowed_at" class="block text-sm font-medium text-gray-700 mb-2">Borrowed Date</label>
                            <input type="date" id="borrowed_at" name="borrowed_at"
                                   value="{{ old('borrowed_at', \Carbon\Carbon::parse($borrowing->borrowed_at)->format('Y-m-d')) }}"
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                            <input type="date" id="due_date" name="due_date"
                                   value="{{ old('due_date', $borrowing->due_date ? \Carbon\Carbon::parse($borrowing->due_date)->format('Y-m-d') : '') }}"
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                <option value="pending" {{ $borrowing->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="overdue" {{ $borrowing->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="returned" {{ $borrowing->status == 'returned' ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary font-semibold">
                                Update Borrowing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
