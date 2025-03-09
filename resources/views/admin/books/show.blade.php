<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Book') }}  <!-- Заголовок страницы -->
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">{{ $book->title }}</h1>

                    <!-- Описание книги -->
                    <p><strong>Автор:</strong> {{ $book->author }}</p>
                    <p><strong>Описание:</strong> {{ $book->description }}</p>

                    <!-- Кнопка для возврата на список книг -->
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary mt-4">Вернуться к списку</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
