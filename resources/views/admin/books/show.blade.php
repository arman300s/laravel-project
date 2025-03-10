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

                <!-- Кнопки действий -->
                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('books.download', $book->id) }}"
                       class="inline-flex items-center px-6 py-3  text-black text-lg font-medium rounded-lg shadow-md hover:bg-blue-700 transition">
                        📥 Download PDF
                    </a>

                    @if(Auth::check())
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.books.index') : route('user.books.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-800 text-lg font-medium rounded-lg shadow-md ">
                            🔙 Back to list
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
