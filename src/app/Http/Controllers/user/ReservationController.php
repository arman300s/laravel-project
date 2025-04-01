<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $availableBooks = Book::all();
        return view('user.reservations.create', compact('availableBooks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'reservation_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        Reservation::create([
            'user_id' => Auth::id(),
            'book_id' => $validated['book_id'],
            'reservation_date' => $validated['reservation_date'],
            'expiration_date' => now()->addDays(14),
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('user.reservations.index')
            ->with('success', 'Reservation requested successfully.');
    }

    public function show(Reservation $reservation)
    {
        if (auth()->user()->role !== 'admin' && auth()->id() !== $reservation->user_id) {
            abort(403);
        }

        return view('user.reservations.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        if ($reservation->status === 'pending') {
            $reservation->update(['status' => 'rejected']);
            return back()->with('success', 'Reservation canceled successfully.');
        }

        return back()->with('error', 'Only pending reservations can be canceled.');
    }
}
