<x-user.layout>
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">Reservation Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-md font-medium text-gray-900">Book Information</h3>
                    <dl class="mt-4 space-y-4">
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Title</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reservation->book->title }}</dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Author</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reservation->book->author }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-md font-medium text-gray-900">Reservation Details</h3>
                    <dl class="mt-4 space-y-4">
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Reservation Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reservation->reservation_date->format('M d, Y') }}</dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Expiration Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reservation->expiration_date->format('M d, Y') }}</dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $reservation->status === 'approved' ? 'bg-green-100 text-green-800' :
                                    ($reservation->status === 'rejected' ? 'bg-red-100 text-red-800' :
                                    ($reservation->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($reservation->notes)
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-900">Your Notes</h3>
                    <div class="mt-2 text-sm text-gray-500">
                        {{ $reservation->notes }}
                    </div>
                </div>
            @endif

            <div class="mt-6">
                @if($reservation->status === 'pending')
                    <form action="{{ route('user.reservations.cancel', $reservation->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none transition ease-in-out duration-150"
                                onclick="return confirm('Are you sure you want to cancel this reservation?')">
                            Cancel Reservation
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-user.layout>
