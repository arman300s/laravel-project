<x-reservation-layout>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-800">Reservation Details</h3>
            <div class="flex space-x-2">
                <a href="{{ route('admin.reservations.edit', $reservation) }}" class="px-3 py-1 border border-indigo-500 text-indigo-600 rounded-md text-sm hover:bg-indigo-50 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="px-3 py-1 border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition">
                    Back to List
                </a>
            </div>
        </div>

        <div class="px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <h4 class="text-xl font-semibold text-gray-900">Reservation Record №{{ $reservation->id }}</h4>
                        <div class="mt-2 flex items-center">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($reservation->status === 'active') bg-blue-100 text-blue-800
                                @elseif($reservation->status === 'completed') bg-green-100 text-green-800
                                @elseif($reservation->status === 'canceled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2">Book Information</h5>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h6 class="font-medium text-gray-900">{{ $reservation->book->title }}</h6>
                            <p class="text-sm text-gray-600">by {{ $reservation->book->author ? $reservation->book->author->getFullNameAttribute() : 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mt-1">ISBN: {{ $reservation->book->isbn ?? 'N/A' }}</p>
                            <p class="mt-2">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($reservation->book->status === 'available') bg-green-100 text-green-800
                                    @elseif($reservation->book->status === 'reserved') bg-blue-100 text-blue-800
                                    @elseif($reservation->book->status === 'unavailable') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($reservation->book->status) }}
                                </span>
                                <span class="ml-2 text-gray-600 text-sm">
                                    (Available copies: {{ $reservation->book->available_copies }})
                                </span>
                            </p>
                        </div>
                    </div>

                    <div>
                        <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2">User Information</h5>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h6 class="font-medium text-gray-900">{{ $reservation->user->name }}</h6>
                            <p class="text-sm text-gray-600">{{ $reservation->user->email }}</p>
                        </div>
                    </div>

                    @if($reservation->description)
                        <div class="prose max-w-none text-gray-700">
                            <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2">Notes</h5>
                            <p>{{ $reservation->description }}</p>
                        </div>
                    @endif
                </div>

                <div class="md:col-span-1">
                    <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                        <h5 class="text-sm font-medium text-gray-500 uppercase mb-3 border-b pb-2">Timeline</h5>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Reserved At</dt>
                                <dd class="text-sm text-gray-900">{{ $reservation->reserved_at->format('M j, Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Expires At</dt>
                                <dd class="text-sm text-gray-900">{{ $reservation->expires_at->format('M j, Y H:i') }}</dd>
                            </div>
                            @if($reservation->status === 'canceled' && $reservation->canceled_at)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Canceled At</dt>
                                    <dd class="text-sm text-gray-900">{{ $reservation->canceled_at->format('M j, Y H:i') }}</dd>
                                </div>
                            @endif
                            @if($reservation->status === 'completed' && $reservation->completed_at)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Completed At</dt>
                                    <dd class="text-sm text-gray-900">{{ $reservation->completed_at->format('M j, Y H:i') }}</dd>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Created</dt>
                                <dd class="text-sm text-gray-900">{{ $reservation->created_at->format('M j, Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Last Updated</dt>
                                <dd class="text-sm text-gray-900">{{ $reservation->updated_at->diffForHumans() }}</dd>
                            </div>
                        </dl>

                        @if($reservation->status !== 'canceled' && $reservation->status !== 'completed')
                            <div class="pt-4 border-t mt-4 space-y-3">
                                <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                    @csrf
                                    <button type="submit" class="w-35 px-4 py-2 border border-red-300 rounded-lg text-red-700 bg-red-100 hover:bg-red-200 transition duration-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Cancel Reservation
                                    </button>
                                </form>

                                @if($reservation->status === 'active')
                                    <form action="{{ route('admin.reservations.create-borrowing', $reservation) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-35 px-6 py-2 mt-2 border border-green-300 rounded-lg text-green-700 bg-green-100 hover:bg-green-200 transition duration-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
                                            </svg>
                                            Create Borrowing
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-reservation-layout>
