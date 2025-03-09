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
                    <h1 class="text-2xl font-semibold mb-6">Редактировать пользователя</h1>

                    <!-- Форма редактирования пользователя -->
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Имя пользователя -->
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <!-- Пароль (если не хотите менять, оставьте пустым) -->
                        <div class="form-group">
                            <label for="password">Пароль (оставьте пустым, если не хотите изменять)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Подтверждение пароля</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <!-- Роль пользователя -->
                        <div class="form-group">
                            <label for="role">Роль</label>
                            <select name="role" class="form-control" required>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Пользователь</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Администратор</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Обновить пользователя</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
