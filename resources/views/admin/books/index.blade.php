<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Приветственное сообщение -->
                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-4 text-gray-900">📚 Welcome, Admin!</h1>
                    <p class="text-gray-600 mb-4">Here you can manage books efficiently.</p>

                    <!-- Кнопка добавления новой книги -->
                    <a href="{{ route('admin.books.create') }}"
                       class="bg-indigo-600 text-black px-6 py-2 rounded-md font-semibold hover:bg-indigo-700 transition-all duration-300 shadow-md">
                        ➕ Add Book
                    </a>
                </div>

                <!-- Список книг -->
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($books as $book)
                        <div class="bg-gray-100 p-5 rounded-lg shadow-md hover:shadow-lg transition-all">
                            <h2 class="text-xl font-bold mb-2 text-gray-800">{{ $book->title }}</h2>
                            <p class="text-gray-600 mb-3">{{ Str::limit($book->description, 150) }}</p>

                            <!-- Кнопки управления книгами в один ряд с расстоянием между ними -->
                            <div class="flex items-center space-x-8 mt-4">
                                <!-- Edit -->
                                <a href="{{ route('admin.books.edit', $book->id) }}"
                                   class="text-yellow-500 font-semibold hover:text-yellow-600 transition">
                                    ✏️ Edit
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 font-semibold hover:text-red-600 transition">
                                        🗑️ Delete
                                    </button>
                                </form>
                                <a href="{{ route('admin.books.show', $book->id) }}"
                                   class="text-yellow-500 font-semibold hover:text-yellow-600 transition">
                                    🔎More details
                                </a>
                                <!-- Download PDF -->
                                <a href="{{ route('books.download', $book->id) }}"
                                   class="text-blue-500 font-semibold hover:text-blue-600 transition">
                                    📥 Download PDF
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Пагинация -->
                <div class="mt-6 px-6">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
