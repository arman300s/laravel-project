<x-book-layout>
    <div class="mb-6">
        <h3 class="text-3xl font-bold text-gray-800">Library Catalog</h3>
        <p class="text-gray-600 mt-1">Browse or search for available books.</p>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="p-4 sm:p-6">
            <form method="GET" action="{{ route('user.books.index') }}">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="Search by Title, Author, Category, or ISBN...">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition duration-200">
                        Search
                    </button>
                    @if(request()->has('search') && request('search') !== '')
                        <a href="{{ route('user.books.index') }}" class="w-24 px-4 py-2 text-center border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if($books->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($books as $book)
                <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                    <div class="flex-grow">
                        <h4 class="text-lg font-semibold text-gray-900 m-4 p-4">
                            <a href="{{ route('user.books.show', $book) }}" class="hover:text-blue-700 transition">
                                {{ $book->title }}
                            </a>
                        </h4>
                        <p class="text-sm text-gray-600 m-4 p-4">By {{ $book->author->getFullNameAttribute() ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 m-4 p-4">
                            <span class="font-medium">{{ $book->category->name ?? 'Uncategorized' }}</span> - Published {{ $book->published_year }}
                        </p>
                        <p class="text-sm text-gray-700 line-clamp-3 m-4 p-4">
                            {{ $book->description ?: 'No description available.' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                        <a href="{{ route('user.books.show', $book) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                            View Details &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500">
                @if(request()->has('search') && request('search') !== '')
                    No books found matching your search term "{{ request('search') }}".
                @else
                    There are currently no books in the library catalog.
                @endif
            </p>
        </div>
    @endif

    {{-- Pagination --}}
    <div class="mt-8">
        {{-- Append search query to pagination links --}}
        {{ $books->appends(request()->query())->links() }}
    </div>

</x-book-layout>
