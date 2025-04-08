<x-reservation-layout>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
            <h3 class="text-2xl font-bold text-gray-800">Reservation Details</h3>
            <div class="flex space-x-2 flex-shrink-0">
                <a href="{{ route('admin.reservations.edit', $reservation) }}" class="inline-flex items-center px-3 py-1 border border-indigo-500 text-indigo-600 rounded-md text-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center px-3 py-1 border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition">
                    Back to List
                </a>
            </div>
        </div>

        <div class="px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Main Details --}}
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <h4 class="text-xl font-semibold text-gray-900">Reservation Record №{{ $reservation->id }}</h4>
                        <div class="mt-2 flex items-center">
                             <span @class([
                                'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                'bg-yellow-100 text-yellow-800' => $reservation->status === 'pending',
                                'bg-green-100 text-green-800' => $reservation->status === 'completed',
                                'bg-red-100 text-red-800' => $reservation->status === 'canceled',
                                'bg-gray-100 text-gray-800' => !in_array($reservation->status, ['pending', 'completed', 'canceled']), // Fallback
                            ])>
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2 tracking-wider">Book Information</h5>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h6 class="font-medium text-gray-900">{{ $reservation->book->title }}</h6>
                            <p class="text-sm text-gray-600">by {{ $reservation->book->author ? $reservation->book->author->getFullNameAttribute() : 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mt-1">ISBN: {{ $reservation->book->isbn ?? 'N/A' }}</p>
                            {{-- Add other relevant book details if needed --}}
                        </div>
                    </div>

                    <div>
                        <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2 tracking-wider">User Information</h5>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h6 class="font-medium text-gray-900">{{ $reservation->user->name }}</h6>
                            <p class="text-sm text-gray-600">{{ $reservation->user->email }}</p>
                            {{-- Add other relevant user details if needed --}}
                        </div>
                    </div>

                    @if($reservation->description)
                        <div>
                            <h5 class="font-semibold text-gray-500 text-sm uppercase mb-2 tracking-wider">Notes</h5>
                            <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p>{{ $reservation->description }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Timeline & Actions --}}
                <div class="md:col-span-1">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4">
                        <h5 class="text-sm font-medium text-gray-500 uppercase mb-3 border-b pb-2 tracking-wider">Timeline</h5>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Reserved At</dt>
                                <dd class="text-sm text-gray-900 text-right">{{ $reservation->reserved_at->format('M j, Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Expires At</dt>
                                <dd class="text-sm text-gray-900 text-right">{{ $reservation->expires_at->format('M j, Y H:i') }}</dd>
                            </div>
                            {{-- No Returned At for reservations usually --}}
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Created</dt>
                                <dd class="text-sm text-gray-900 text-right">{{ $reservation->created_at->format('M j, Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Last Updated</dt>
                                <dd class="text-sm text-gray-900 text-right">{{ $reservation->updated_at->diffForHumans() }}</dd>
                            </div>
                        </dl>

                        {{-- Cancel Action --}}
                        @if($reservation->status !== 'canceled' && $reservation->status !== 'completed')
                            <div class="pt-4 border-t mt-4">
                                <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                    @csrf
                                    {{-- Can use POST or add @method('PATCH') depending on route definition --}}
                                    <button type="submit" class="w-full flex justify-center items-center px-4 py-2 border border-orange-300 rounded-lg text-sm font-medium text-orange-700 bg-orange-100 hover:bg-orange-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        Cancel Reservation
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-reservation-layout>
