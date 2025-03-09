<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Books') }}  <!-- Заголовок страницы -->
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Приветственное сообщение -->
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Добро пожаловать, Пользователь!</h1>
                    <p class="text-gray-600">Здесь вы можете просматривать список доступных книг.</p>
                </div>

                <!-- Список книг -->
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($books as $book)
                        <div class="bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transition">
                            <h2 class="text-xl font-semibold mb-2">{{ $book->title }}</h2>
                            <p class="text-gray-700">{{ Str::limit($book->description, 150) }}</p>
                            <a href="{{ route('admin.books.show', $book->id) }}" class="text-blue-500 hover:underline mt-4 inline-block">
                                Подробнее
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
