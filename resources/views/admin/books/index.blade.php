<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Books') }}  <!-- Заголовок страницы -->
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Приветственное сообщение -->
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Добро пожаловать, Администратор!</h1>
                    <p class="text-gray-600">Здесь вы можете управлять книгами.</p>

                    <!-- Кнопка добавления новой книги -->
                    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">Добавить книгу</a>
                </div>

                <!-- Список книг -->
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($books as $book)
                        <div class="bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transition">
                            <h2 class="text-xl font-semibold mb-2">{{ $book->title }}</h2>
                            <p class="text-gray-700">{{ Str::limit($book->description, 150) }}</p>

                            <!-- Кнопки управления книгами -->
                            <div class="mt-4 space-x-4">
                                <!-- Ссылка на редактирование книги -->
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="text-yellow-500 hover:underline">
                                    Редактировать
                                </a>
                                <!-- Форма для удаления книги -->
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Пагинация, если список книг длинный -->
                <div class="mt-4">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
