<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}  <!-- Заголовок страницы панели администратора -->
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Приветственное сообщение -->
                <div class="p-6">
                    <h1 class="text-4xl font-semibold mb-4 text-gray-800">Welcome, Admin!</h1>  <!-- Приветствие администратору -->
                    <p class="text-lg text-gray-600">This is the dashboard where you can manage all aspects of the application.</p>  <!-- Описание -->

                    <!-- Дополнительная информация или действия для администратора -->
                    <div class="mt-6">
                        <a href="{{ route('admin.users.index') }}" class="inline-block bg-blue-600 text-blue-700 px-6 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-all duration-300">
                            Manage Users
                        </a>
                        <a href="{{ route('admin.books.index') }}" class="inline-block bg-green-600 text-blue px-6 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-all duration-300 ml-4">
                            Manage Books
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
