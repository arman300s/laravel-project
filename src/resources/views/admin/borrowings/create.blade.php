<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Borrowing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Add New Borrowing</h1>

                    <form action="{{ route('admin.borrowings.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User</label>
                            <select id="user_id" name="user_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="book_id" class="block text-sm font-medium text-gray-700 mb-2">Book</label>
                            <select id="book_id" name="book_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="borrowed_at" class="block text-sm font-medium text-gray-700 mb-2">Borrowed Date</label>
                            <input type="date" id="borrowed_at" name="borrowed_at"
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                            <input type="date" id="due_date" name="due_date"
                                   value="{{ old('due_date') }}"
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="returned" {{ old('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary font-semibold">
                                Create Borrowing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
