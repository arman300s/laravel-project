<x-borrowing-layout>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-800">Borrowing Details</h3>
            <div class="flex space-x-2">
                <a href="{{ route('admin.borrowings.edit', $borrowing) }}" class="px-3 py-1 border border-indigo-500 text-indigo-600 rounded-md text-sm hover:bg-indigo-50 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.borrowings.index') }}" class="px-3 py-1 border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition">
                    Back to List
                </a>
            </div>
        </div>

        <div class="px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <h4 class="text-xl font-semibold text-gray-900">Borrowing Record №{{ $borrowing->id }}</h4>
                        <div class="mt-2 flex items-center">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($borrowing->status === 'active') bg-green-100 text-green-800
                                @elseif($borrowing->status === 'returned')
                                @elseif($borrowing->status === 'overdue')
                                @else
                                @endif">
                                {{ ucfirst($borrowing->status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2">Book Information</h5>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h6 class="font-medium text-gray-900">{{ $borrowing->book->title }}</h6>
                            <p class="text-sm text-gray-600">by {{ $borrowing->book->author->getFullNameAttribute()}}</p>
                            <p class="text-xs text-gray-500 mt-1">ISBN: {{ $borrowing->book->isbn }}</p>
                        </div>
                    </div>

                    <div>
                        <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2">User Information</h5>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h6 class="font-medium text-gray-900">{{ $borrowing->user->name }}</h6>
                            <p class="text-sm text-gray-600">{{ $borrowing->user->email }}</p>
                        </div>
                    </div>

                    @if($borrowing->description)
                        <div class="prose max-w-none text-gray-700">
                            <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2">Notes</h5>
                            <p>{{ $borrowing->description }}</p>
                        </div>
                    @endif
                </div>

                <div class="md:col-span-1">
                    <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                        <h5 class="text-sm font-medium text-gray-500 uppercase mb-3 border-b pb-2">Timeline</h5>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Borrowed At</dt>
                                <dd class="text-sm text-gray-900">{{ $borrowing->borrowed_at->format('M j, Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Due At</dt>
                                <dd class="text-sm text-gray-900">{{ $borrowing->due_at->format('M j, Y H:i') }}</dd>
                            </div>
                            @if($borrowing->returned_at)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Returned At</dt>
                                    <dd class="text-sm text-gray-900">{{ $borrowing->returned_at->format('M j, Y H:i') }}</dd>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Created</dt>
                                <dd class="text-sm text-gray-900">{{ $borrowing->created_at->format('M j, Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Last Updated</dt>
                                <dd class="text-sm text-gray-900">{{ $borrowing->updated_at->diffForHumans() }}</dd>
                            </div>
                        </dl>

                        @if($borrowing->status !== 'returned')
                            <div class="pt-4 border-t mt-4">
                                <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                                        Mark as Returned
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-borrowing-layout>
