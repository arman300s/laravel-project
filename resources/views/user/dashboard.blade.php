<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Проверка на уведомления -->

                    @if(auth()->user()->notifications->isNotEmpty())
                        <div class="bg-green-100 p-4 rounded-lg mb-6">
                            <strong>Notifications:</strong>
                            @foreach (auth()->user()->notifications as $notification)
                                <div class="alert alert-info">
                                    {{ $notification->data['message'] }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <h1 class="text-2xl font-semibold mb-6">Welcome, {{ auth()->user()->name }}!</h1>
                    <!-- Ваш основной контент -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
