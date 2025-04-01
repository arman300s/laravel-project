<x-user.layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <form method="GET" action="{{ route('user.categories.index') }}">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search categories..."
                               class="rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 w-full">
                        <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            @if($categories->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 text-center">
                        <p class="text-gray-500">No categories found.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categories as $category)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">
                                    <a href="{{ route('user.categories.show', $category) }}" class="hover:text-indigo-600">
                                        {{ $category->name }}
                                    </a>
                                </h3>
                                @if($category->description)
                                    <p class="text-gray-600 mb-4">{{ Str::limit($category->description, 100) }}</p>
                                @endif
                                <a href="{{ route('user.categories.show', $category) }}"
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $categories->appends(['search' => request('search')])->links() }}
                </div>
            @endif
        </div>
    </div>
</x-user.layout>
