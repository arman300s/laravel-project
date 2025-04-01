<x-admin.layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category Details') }}
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

                    <div class="flex items-center justify-start space-x-4">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Are you sure you want to delete this category?')">
                                Delete
                            </button>
                        </form>
                        <a href="{{ route('admin.categories.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Categories
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
