<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Book') }}  <!-- Заголовок страницы -->
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Edit Book</h1>  <!-- Редактировать книгу -->

                    <!-- Вывод ошибок валидации -->
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.books.update', $book->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Поле для названия книги -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>  <!-- Название -->
                            <input type="text" id="title" name="title" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('title', $book->title) }}" required>
                        </div>

                        <!-- Поле для автора -->
                        <div class="mb-4">
                            <label for="author" class="block text-sm font-medium text-gray-700">Author</label>  <!-- Автор -->
                            <input type="text" id="author" name="author" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('author', $book->author) }}" required>
                        </div>

                        <!-- Поле для описания -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>  <!-- Описание -->
                            <textarea id="description" name="description" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>{{ old('description', $book->description) }}</textarea>
                        </div>

                        <!-- Кнопка отправки формы -->
                        <button type="submit" class="inline-block bg-indigo-600 text-black px-6 py-2 rounded-md text-sm font-medium mt-4 hover:bg-indigo-700 transition-all duration-300">
                            Update Book
                        </button>  <!-- Обновить книгу -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
