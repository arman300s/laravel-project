<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Book Views
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-4 text-gray-800">📊 Book Views</h1>

                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2">User</th>
                            <th class="px-4 py-2">Book</th>
                            <th class="px-4 py-2">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($views as $view)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $view->user->name }}</td>
                                <td class="px-4 py-2">{{ $view->book->title }}</td>
                                <td class="px-4 py-2">{{ $view->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
