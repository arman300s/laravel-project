<x-user.layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $category->name }}</h3>
                        <p class="mt-1 text-sm text-gray-600">Slug: {{ $category->slug }}</p>
                    </div>

                    @if($category->description)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900">Description</h4>
                            <p class="mt-1 text-gray-600">{{ $category->description }}</p>
                        </div>
                    @endif

                    <div class="flex items-center">
                        <a href="{{ route('user.categories.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Categories
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-user.layout>
