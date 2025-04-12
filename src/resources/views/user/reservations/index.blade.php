<x-reservation-layout>
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800">My Reservations</h3>
        <a href="{{ route('user.reservations.create') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
            New Reservation
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Search Form --}}
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="p-4 sm:p-6">
            <form method="GET" action="{{ route('user.reservations.index') }}">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="Search by Book Title, Status...">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700 transition duration-200">
                        Search
                    </button>
                    @if(request()->has('search') && request('search') !== '')
                        <a href="{{ route('user.reservations.index') }}" class="px-4 py-2 text-center border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrowed At</th> {{-- Changed to Borrowed At for style --}}
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due At</th> {{-- Changed to Due At for style --}}
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($reservations as $reservation)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $reservation->book->title }}</div>
                            <div class="text-xs text-gray-500">by {{ $reservation->book->author ? $reservation->book->author->getFullNameAttribute() : 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $reservation->reserved_at->format('M d, Y') }} {{-- Formatted to match Borrowings --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $reservation->expires_at->format('M d, Y') }} {{-- Formatted to match Borrowings --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span @class([
                                'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                'bg-yellow-100 text-yellow-800' => $reservation->status === 'pending',
                                'bg-green-100 text-green-800' => $reservation->status === 'completed',
                                'bg-gray-100 text-gray-800' => $reservation->status === 'canceled',
                                'bg-gray-100 text-gray-800' => !in_array($reservation->status, ['pending', 'completed', 'canceled']), // Fallback
                            ])>
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 justify-center">
                                <a href="{{ route('user.reservations.show', $reservation) }}" class="text-center text-blue-600 hover:text-blue-900" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                </a>
                                @if ($reservation->status === 'pending') {{-- Keep cancel functionality --}}
                                <form action="{{ route('user.reservations.cancel', $reservation) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Cancel Reservation">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No borrowings found matching your criteria.</td> {{-- Changed empty state message --}}
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($reservations->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $reservations->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-reservation-layout>
