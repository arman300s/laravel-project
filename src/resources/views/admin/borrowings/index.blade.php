<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Borrowings Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Borrowings List</h1>

                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <a href="{{ route('admin.borrowings.create') }}" class="btn btn-primary font-semibold">+ Add New Borrowing</a>
                    </div>

                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">User</th>
                            <th class="px-4 py-2 border">Book</th>
                            <th class="px-4 py-2 border">Borrowed At</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($borrowings as $borrowing)
                            <tr class="hover:bg-gray-50 transition-all duration-300">
                                <td class="px-4 py-2 border">{{ $borrowing->id }}</td>
                                <td class="px-4 py-2 border">{{ $borrowing->user->name }}</td>
                                <td class="px-4 py-2 border">{{ $borrowing->book->title }}</td>
                                <td class="px-4 py-2 border">
                                    {{ $borrowing->borrowed_at ? \Carbon\Carbon::parse($borrowing->borrowed_at)->format('d-m-Y') : 'Not specified' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    <a href="{{ route('admin.borrowings.show', $borrowing->id) }}" class="text-blue-500">View</a> |
                                    <a href="{{ route('admin.borrowings.edit', $borrowing->id) }}" class="text-yellow-500">Edit</a> |
                                    <form action="{{ route('admin.borrowings.destroy', $borrowing->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500" onclick="return confirm('Delete this borrowing?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $borrowings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
