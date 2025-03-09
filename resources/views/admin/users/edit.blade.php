<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Edit User</h1>

                    <!-- Форма редактирования пользователя -->
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Имя пользователя -->
                        <div class="form-group">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label> <!-- Имя -->
                            <input type="text" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label> <!-- Email -->
                            <input type="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <!-- Пароль (если не хотите менять, оставьте пустым) -->
                        <div class="form-group">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password (leave empty if you don't want to change)</label> <!-- Пароль -->
                            <input type="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Подтверждение пароля -->
                        <div class="form-group">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Password Confirmation</label> <!-- Подтверждение пароля -->
                            <input type="password" name="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Роль пользователя -->
                        <div class="form-group">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label> <!-- Роль -->
                            <select name="role" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option> <!-- Пользователь -->
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option> <!-- Администратор -->
                            </select>
                        </div>

                        <!-- Кнопка для обновления пользователя -->
                        <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
