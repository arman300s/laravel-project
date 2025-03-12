<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-4xl font-semibold mb-4 text-gray-800">Welcome, Admin!</h1>
                    <p class="text-lg text-gray-600">This is the dashboard where you can manage all aspects of the application.</p>

                    <div class="mt-6 flex flex-wrap gap-4">
                        <a href="{{ route('admin.users.index') }}"
                           class="px-6 py-3 rounded-lg shadow-md font-semibold transition duration-300
                           {{ request()->routeIs('admin.users.index') ? ' text-black' : 'bg-blue-600 text-black hover:bg-blue-700' }}">
                            👤 Manage Users
                        </a>

                        <a href="{{ route('admin.books.index') }}"
                           class="px-6 py-3 rounded-lg shadow-md font-semibold transition duration-300
                           {{ request()->routeIs('admin.books.index') ? ' text-black' : 'bg-green-600 text-black hover:bg-green-700' }}">
                            📚 Manage Books
                        </a>

                        <a href="{{ route('admin.book.views') }}"
                           class="px-6 py-3 rounded-lg shadow-md font-semibold transition duration-300
                           {{ request()->routeIs('admin.book.views') ?  : ' text-black hover:bg-purple-700' }}">
                            📊 View Book Views
                        </a>
                    </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
