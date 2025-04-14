<x-book-layout>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-800">Book Details</h3>
            <div class="flex space-x-2">
                <a href="{{ route('admin.books.edit', $book) }}" class="px-3 py-1 border border-indigo-500 text-indigo-600 rounded-md text-sm hover:bg-indigo-50 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.books.index') }}" class="px-3 py-1 border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition">
                    Back to List
                </a>
            </div>
        </div>

        <div class="px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <h4 class="text-2xl font-semibold text-gray-900">{{ $book->title }}</h4>
                        <p class="text-lg text-gray-600 mt-1">by {{ $book->author->getFullNameAttribute() ?? 'Unknown Author' }}</p>
                    </div>

                    <div class="prose max-w-none text-gray-700">
                        <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2">Description</h5>
                        {!! nl2br(e($book->description)) ?: '<p><em>No description provided.</em></p>' !!}
                    </div>

                    <div>
                        <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2">Downloads</h5>
                        <div class="flex flex-wrap gap-3">
                            @if($book->file_pdf)
                                <a href="{{ route('books.download', ['book' => $book, 'format' => 'pdf']) }}" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium hover:bg-red-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /> </svg>
                                    PDF
                                </a>
                            @endif
                            @if($book->file_docx)
                                <a href="{{ route('books.download', ['book' => $book, 'format' => 'docx']) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium hover:bg-blue-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /> </svg>
                                    DOCX
                                </a>
                            @endif
                            @if($book->file_epub)
                                <a href="{{ route('books.download', ['book' => $book, 'format' => 'epub']) }}" class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium hover:bg-green-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /> </svg>
                                    EPUB
                                </a>
                            @endif
                            @if(!$book->file_pdf && !$book->file_docx && !$book->file_epub)
                                <p class="text-sm text-gray-500 italic">No downloadable files available.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                        <h5 class="text-sm font-medium text-gray-500 uppercase mb-3 border-b pb-2">Book Information</h5>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">ISBN</dt>
                                <dd class="text-sm text-gray-900 font-mono">{{ $book->isbn }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Category</dt>
                                <dd class="text-sm text-gray-900">{{ $book->category->name ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Published</dt>
                                <dd class="text-sm text-gray-900">{{ $book->published_year }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Available</dt>
                                <dd class="text-sm font-semibold {{ $book->available_copies > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $book->available_copies }} copies
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Total</dt>
                                <dd class="text-sm text-gray-900">{{ $book->total_copies }} copies</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Added On</dt>
                                <dd class="text-sm text-gray-900">{{ $book->created_at->format('M j, Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Last Updated</dt>
                                <dd class="text-sm text-gray-900">{{ $book->updated_at->diffForHumans() }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-book-layout>
