<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Borrowing Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Borrowing Details</h1>

                    @if(session('success'))
                        <div class="alert alert-success mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="space-y-4 mb-8">
                        <div class="border-b pb-4">
                            <h3 class="text-sm font-medium text-gray-500">User</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $borrowing->user->name }}</p>
                        </div>

                        <div class="border-b pb-4">
                            <h3 class="text-sm font-medium text-gray-500">Book</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $borrowing->book->title }}</p>
                        </div>

                        <div class="border-b pb-4">
                            <h3 class="text-sm font-medium text-gray-500">Borrowed Date</h3>
                            <p class="mt-1 text-lg text-gray-900">
                                @if($borrowing->borrowed_at)
                                    {{ \Carbon\Carbon::parse($borrowing->borrowed_at)->format('d-m-Y') }}
                                @else
                                    <span class="text-red-500">Not specified</span>
                                @endif
                            </p>
                        </div>

                        <div class="border-b pb-4">
                            <h3 class="text-sm font-medium text-gray-500">Due Date</h3>
                            <p class="mt-1 text-lg text-gray-900">
                                @if($borrowing->due_date)
                                    {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d-m-Y') }}
                                @else
                                    <span class="text-red-500">Not specified</span>
                                @endif
                            </p>
                        </div>

                        <div class="border-b pb-4">
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ ucfirst($borrowing->status) }}</p>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('admin.borrowings.edit', $borrowing->id) }}"
                           class="text-blue-500 hover:text-blue-700">
                            Edit
                        </a>
                        <span>|</span>
                        <form action="{{ route('admin.borrowings.destroy', $borrowing->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="return confirm('Are you sure you want to delete this borrowing?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
