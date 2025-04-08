<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? 'Reservation' }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 shadow-lg rounded-lg">
        {{ $slot }}
    </div>
</x-app-layout>
