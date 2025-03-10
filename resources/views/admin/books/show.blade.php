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
                <h1 class="text-3xl font-semibold mb-6 text-gray-800">{{ $book->title }}</h1> <!-- Название книги -->

                <!-- Описание книги -->
                <p class="text-lg mb-4"><strong class="font-medium text-gray-800">👤Author:</strong> {{ $book->author }}</p> <!-- Автор -->
                <p class="text-lg"><strong class="font-medium text-gray-800">📚Description:</strong> {{ $book->description }}</p> <!-- Описание -->

                <a href="{{ route('books.download', $book->id) }}"
                   class="  text-black font-bold text-lg ">
                    📥 <strong>Download PDF</strong>
                </a>

                <!-- Кнопка для возврата на список книг -->
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ route('admin.books.index') }}" class="inline-block bg-gray-600 text-black px-6 py-2 rounded-md text-sm font-medium hover:bg-gray-700 mt-6 transition-all duration-300">
                        Back to List
                    </a> <!-- Вернуться к списку -->
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
