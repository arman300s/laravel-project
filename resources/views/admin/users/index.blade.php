<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users Management') }} <!-- Заголовок страницы -->
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Users List</h1> <!-- Список пользователей -->

                    <!-- Кнопка для добавления нового пользователя -->
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary font-semibold mb-4 ">Add New User</a>


                    <!-- Таблица пользователей -->
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Name</th> <!-- Имя -->
                            <th class="px-4 py-2 border">Email</th> <!-- Email -->
                            <th class="px-4 py-2 border">Role</th> <!-- Роль -->
                            <th class="px-4 py-2 border">Actions</th> <!-- Действия -->
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 transition-all duration-300">
                                <td class="px-4 py-2 border">{{ $user->id }}</td>
                                <td class="px-4 py-2 border">{{ $user->name }}</td>
                                <td class="px-4 py-2 border">{{ $user->email }}</td>
                                <td class="px-4 py-2 border">{{ $user->role }}</td>
                                <td class="px-4 py-2 border">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-500">Edit</a> <!-- Редактировать -->

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500">Delete</button> <!-- Удалить -->
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                    <!-- Пагинация -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
