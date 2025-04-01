<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-3xl font-semibold mb-6 text-gray-900">Available Books</h1>
                    <p class="text-gray-600 mb-6">Browse through a list of books available for borrowing. Click "Borrow" to get started.</p>
                </div>

                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($books as $book)
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition-all ease-in-out duration-300">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-3">
                                <span class="inline-block mr-2">📗</span>{{ $book->title }}
                            </h2>
                            <p class="text-gray-700 mb-4">{{ Str::limit($book->description, 150) }}</p>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('admin.books.show', $book->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold">
                                    More details
                                </a>
                                <a href="{{ route('books.borrow', $book->id) }}"
                                   class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-700 transition duration-200">
                                    📚 <strong>Borrow</strong>
                                </a>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('books.download', $book->id) }}"
                                   class="text-black font-bold text-lg hover:underline mt-2 block">
                                    📥 <strong>Download PDF</strong>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
