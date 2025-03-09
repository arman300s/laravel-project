<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Book') }}  <!-- Заголовок страницы -->
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Добавить книгу</h1>

                    <!-- Вывод ошибок валидации -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.books.store') }}" method="POST">
                        @csrf

                        <!-- Поле для названия книги -->
                        <div class="form-group">
                            <label for="title">Название</label>
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                        </div>

                        <!-- Поле для автора -->
                        <div class="form-group">
                            <label for="author">Автор</label>
                            <input type="text" class="form-control" name="author" value="{{ old('author') }}" required>
                        </div>

                        <!-- Поле для описания -->
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <textarea class="form-control" name="description" required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Кнопка отправки формы -->
                        <button type="submit" class="btn btn-primary mt-4">Добавить книгу</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
