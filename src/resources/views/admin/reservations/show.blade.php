<x-admin.layout header="Reservation Details">
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Reservation Information</h3>
                    <dl class="mt-4 space-y-4">
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Reservation ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reservation->id }}</dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">User</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reservation->user->name }}</dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Book</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reservation->book->title }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900">Dates & Status</h3>
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
                    <h3 class="text-lg font-medium text-gray-900">Notes</h3>
                    <div class="mt-2 prose max-w-none text-gray-500">
                        {{ $reservation->notes }}
                    </div>
                </div>
            @endif

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.reservations.edit', $reservation->id) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition ease-in-out duration-150">
                    Edit
                </a>
                <form action="{{ route('admin.reservations.destroy', $reservation->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-700 active:bg-red-900 focus:outline-none transition ease-in-out duration-150"
                            onclick="return confirm('Are you sure you want to delete this reservation?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin.layout>
