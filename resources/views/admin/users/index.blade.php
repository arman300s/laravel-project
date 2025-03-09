<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users Management') }}  <!-- Заголовок страницы -->
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Список пользователей</h1>

                    <!-- Кнопка для добавления нового пользователя -->
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-4">Добавить нового пользователя</a>

                    <!-- Таблица пользователей -->
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                        <tr>
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Имя</th>
                            <th class="px-4 py-2 border">Email</th>
                            <th class="px-4 py-2 border">Роль</th>
                            <th class="px-4 py-2 border">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-2 border">{{ $user->id }}</td>
                                <td class="px-4 py-2 border">{{ $user->name }}</td>
                                <td class="px-4 py-2 border">{{ $user->email }}</td>
                                <td class="px-4 py-2 border">{{ $user->role }}</td>
                                <td class="px-4 py-2 border">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-500">Редактировать</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500">Удалить</button>
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
