<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Reservation::class, 'reservation');
    }

    public function index()
    {
        if (auth()->user()->isAdmin()) {
            return Reservation::with(['user', 'book'])->get();
        }

        return auth()->user()->reservations()->with('book')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'expires_at' => 'required|date|after:now',
            'description' => 'nullable|string',
            'status' => $request->user()->isAdmin()
                ? 'sometimes|in:pending,completed,canceled'
                : 'sometimes|in:pending'
        ]);

        $validated['user_id'] = auth()->id();
        $validated['reserved_at'] = now();

        if (!$request->user()->isAdmin()) {
            $validated['status'] = 'pending';
        }

        return Reservation::create($validated);
    }

    public function show(Reservation $reservation)
    {
        return $reservation->load(['user', 'book']);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,completed,canceled',
            'description' => 'nullable|string',
        ]);

        $reservation->update($validated);
        return $reservation;
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->noContent();
    }

    public function cancel(Reservation $reservation)
    {
        $this->authorize('cancel', $reservation);

        $reservation->update([
            'status' => 'canceled',
            'expires_at' => now()
        ]);

        return $reservation;
    }
}
