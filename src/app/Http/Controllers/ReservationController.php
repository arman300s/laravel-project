<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Borrowing;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations for users.
     */
    public function userIndex(Request $request)
    {
        $search = $request->input('search');

        $reservations = auth()->user()->reservations()
            ->with('book')
            ->when($search, function($query, $search) {
                return $query->whereHas('book', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->orderBy('reserved_at', 'desc')
            ->paginate(10);

        return view('user.reservations.index', compact('reservations', 'search'));
    }

    /**
     * Display the specified reservation for users.
     */
    public function userShow(Reservation $reservation)
    {
        $this->ensureUserOwnsReservation($reservation);
        $reservation->load('book');
        return view('user.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for creating a new reservation for users.
     */
    public function userCreate()
    {
        $books = Book::where('status', '!=', Book::STATUS_UNAVAILABLE)->get();
        return view('user.reservations.create', compact('books'));
    }

    /**
     * Store a newly created reservation for users.
     */
    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'expires_at' => 'required|date|after:now',
            'description' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated) {
            $book = Book::lockForUpdate()->findOrFail($validated['book_id']);
            $user = auth()->user();

            if (in_array($book->status, [Book::STATUS_UNAVAILABLE, Book::STATUS_ARCHIVED, Book::STATUS_LOST])) {
                return back()->withErrors(['book_id' => 'This book cannot be reserved.']);
            }

            $existingReservation = Reservation::where('user_id', $user->id)
                ->where('book_id', $validated['book_id'])
                ->whereIn('status', [Reservation::STATUS_PENDING, Reservation::STATUS_ACTIVE])
                ->first();

            if ($existingReservation) {
                return back()->withErrors(['book_id' => 'You already have a reservation for this book.']);
            }

            $reservationData = [
                'user_id' => $user->id,
                'book_id' => $validated['book_id'],
                'reserved_at' => now(),
                'expires_at' => $validated['expires_at'],
                'description' => $validated['description'] ?? null,
            ];

            if ($book->isAvailable()) {
                $reservationData['status'] = Reservation::STATUS_ACTIVE;
                $book->decrement('available_copies');

                if ($book->available_copies <= 0) {
                    $book->status = Book::STATUS_RESERVED;
                    $book->save();
                }
            } else {
                $reservationData['status'] = Reservation::STATUS_PENDING;
            }

            $reservation = Reservation::create($reservationData);

            return redirect()->route('user.reservations.index')
                ->with('success', $reservation->status === Reservation::STATUS_ACTIVE
                    ? 'Book successfully reserved.'
                    : 'Your reservation has been added to the waiting list.');
        });
    }

    /**
     * Display a listing of reservations for admin.
     */
    public function adminIndex(Request $request)
    {
        $search = $request->input('search');

        $reservations = Reservation::with(['user', 'book'])
            ->when($search, function($query, $search) {
                return $query->whereHas('book', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->orderBy('reserved_at', 'desc')
            ->paginate(10);

        return view('admin.reservations.index', compact('reservations', 'search'));
    }

    /**
     * Show the form for creating a new reservation for admin.
     */
    public function adminCreate()
    {
        $books = Book::all();
        $users = User::all();
        return view('admin.reservations.create', compact('books', 'users'));
    }

    /**
     * Store a newly created reservation for admin.
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'expires_at' => 'required|date|after:now',
            'status' => 'sometimes|in:pending,active,completed,canceled',
            'description' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated) {
            $book = Book::lockForUpdate()->findOrFail($validated['book_id']);

            $reservationData = [
                'user_id' => $validated['user_id'],
                'book_id' => $validated['book_id'],
                'reserved_at' => now(),
                'expires_at' => $validated['expires_at'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'] ?? 'pending',
            ];

            if ($reservationData['status'] === 'active') {
                if ($book->available_copies > 0) {
                    $book->decrement('available_copies');

                    if ($book->available_copies <= 0) {
                        $book->update(['status' => Book::STATUS_RESERVED]);
                    }
                } else {
                    $reservationData['status'] = 'pending';
                }
            }

            Reservation::create($reservationData);

            return redirect()->route('admin.reservations.index')
                ->with('success', 'Reservation created successfully.');
        });
    }

    /**
     * Display the specified reservation for admin.
     */
    public function adminShow(Reservation $reservation)
    {
        $reservation->load(['user', 'book']);
        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified reservation.
     */
    public function adminEdit(Reservation $reservation)
    {
        $books = Book::all();
        $users = User::all();
        return view('admin.reservations.edit', compact('reservation', 'books', 'users'));
    }

    /**
     * Update the specified reservation for admin.
     */
    public function adminUpdate(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'book_id' => 'sometimes|exists:books,id',
            'reserved_at' => 'sometimes|date',
            'expires_at' => 'sometimes|date|after:reserved_at',
            'status' => 'sometimes|in:pending,active,completed,canceled',
            'description' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated, $reservation) {
            $originalStatus = $reservation->status;
            $newStatus = $validated['status'] ?? $originalStatus;
            $bookChanged = isset($validated['book_id']) && ($validated['book_id'] != $reservation->book_id);

            if ($bookChanged) {
                $newBook = Book::lockForUpdate()->findOrFail($validated['book_id']);
                $oldBook = $reservation->book;

                if ($originalStatus === 'active') {
                    $oldBook->increment('available_copies');
                    if ($oldBook->available_copies > 0) {
                        $oldBook->update(['status' => Book::STATUS_AVAILABLE]);
                    }
                }

                if ($newStatus === 'active') {
                    if ($newBook->available_copies > 0) {
                        $newBook->decrement('available_copies');
                        if ($newBook->available_copies <= 0) {
                            $newBook->update(['status' => Book::STATUS_RESERVED]);
                        }
                    } else {
                        $newStatus = 'pending';
                    }
                }
            } else {
                if ($originalStatus !== $newStatus) {
                    $book = $reservation->book;

                    if ($originalStatus === 'active' && $newStatus !== 'active') {
                        $book->increment('available_copies');
                        if ($book->available_copies > 0) {
                            $book->update(['status' => Book::STATUS_AVAILABLE]);
                        }
                        $this->activateNextPendingReservation($book->id);
                    } elseif ($newStatus === 'active' && $originalStatus !== 'active') {
                        if ($book->available_copies > 0) {
                            $book->decrement('available_copies');
                            if ($book->available_copies <= 0) {
                                $book->update(['status' => Book::STATUS_RESERVED]);
                            }
                        } else {
                            return back()->withErrors(['status' => 'No available copies to activate this reservation.']);
                        }
                    }
                }
            }

            $validated['status'] = $newStatus;
            $reservation->update($validated);

            return redirect()->route('admin.reservations.index')
                ->with('success', 'Reservation updated successfully.');
        });
    }

    /**
     * Remove the specified reservation for admin.
     */
    public function adminDestroy(Reservation $reservation)
    {
        return DB::transaction(function () use ($reservation) {
            if (in_array($reservation->status, ['active'])) {
                return back()->with('error', 'Cannot delete active reservations.');
            }

            if ($reservation->status === 'active') {
                $reservation->book->increment('available_copies');
                if ($reservation->book->available_copies > 0) {
                    $reservation->book->update(['status' => Book::STATUS_AVAILABLE]);
                }
                $this->activateNextPendingReservation($reservation->book_id);
            }

            $reservation->delete();

            return redirect()->route('admin.reservations.index')
                ->with('success', 'Reservation deleted successfully.');
        });
    }

    /**
     * Cancel reservation for users.
     */
    public function userCancel(Reservation $reservation)
    {
        $this->ensureUserOwnsReservation($reservation);

        return DB::transaction(function () use ($reservation) {
            if (!$reservation->canBeCanceled()) {
                return back()->with('error', 'This reservation cannot be canceled.');
            }

            $book = $reservation->book;
            $wasActive = $reservation->isActive;

            $reservation->update([
                'status' => Reservation::STATUS_CANCELED,
                'expires_at' => now(),
                'canceled_at' => now(),
            ]);

            if ($wasActive) {
                $book->increment('available_copies');
                if ($book->available_copies > 0 && $book->status === Book::STATUS_RESERVED) {
                    $book->update(['status' => Book::STATUS_AVAILABLE]);
                }
                $this->activateNextPendingReservation($book->id);
            }

            return redirect()->route('user.reservations.index')
                ->with('success', 'Reservation canceled successfully.');
        });
    }

    /**
     * Cancel reservation for admin.
     */
    public function adminCancel(Reservation $reservation)
    {
        return DB::transaction(function () use ($reservation) {
            if ($reservation->status === 'canceled') {
                return back()->with('warning', 'Reservation already canceled.');
            }

            $wasActive = $reservation->status === 'active';
            $book = $reservation->book;

            $reservation->update([
                'status' => 'canceled',
                'expires_at' => now()
            ]);

            if ($wasActive) {
                $book->increment('available_copies');
                if ($book->available_copies > 0) {
                    $book->update(['status' => Book::STATUS_AVAILABLE]);
                }
                $this->activateNextPendingReservation($book->id);
            }

            return redirect()->route('admin.reservations.index')
                ->with('success', 'Reservation canceled successfully.');
        });
    }

    /**
     * Create borrowing from reservation for users.
     */
    public function userCreateBorrowing(Reservation $reservation)
    {
        $this->ensureUserOwnsReservation($reservation);

        return DB::transaction(function () use ($reservation) {
            if ($reservation->status !== Reservation::STATUS_ACTIVE) {
                return back()->with('error', 'Only active reservations can be converted to borrowings.');
            }

            $book = $reservation->book;
            $user = $reservation->user;

            $borrowing = Borrowing::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'from_reservation_id' => $reservation->id,
                'borrowed_at' => now(),
                'due_at' => now()->addDays(14),
                'status' => Borrowing::STATUS_ACTIVE,
                'description' => 'Created from reservation #' . $reservation->id,
            ]);

            $reservation->update([
                'status' => Reservation::STATUS_COMPLETED,
                'expires_at' => now()
            ]);

            return redirect()->route('user.borrowings.index')
                ->with('success', 'Book successfully borrowed from your reservation.');
        });
    }

    /**
     * Create borrowing from reservation for admin.
     */
    public function adminCreateBorrowing(Reservation $reservation)
    {
        return DB::transaction(function () use ($reservation) {
            if ($reservation->status !== Reservation::STATUS_ACTIVE) {
                return back()->with('error', 'Only active reservations can be converted to borrowings.');
            }

            $book = $reservation->book;
            $user = $reservation->user;

            $borrowing = Borrowing::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'from_reservation_id' => $reservation->id,
                'borrowed_at' => now(),
                'due_at' => now()->addDays(14),
                'status' => Borrowing::STATUS_ACTIVE,
                'description' => 'Created from reservation #' . $reservation->id,
            ]);

            $reservation->update([
                'status' => Reservation::STATUS_COMPLETED,
                'expires_at' => now()
            ]);

            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Borrowing successfully created from reservation.');
        });
    }

    /**
     * Activate next pending reservation for a book.
     */
    public function activateNextPendingReservation($bookId)
    {
        $book = Book::lockForUpdate()->findOrFail($bookId);

        if ($book->available_copies > 0) {
            $nextReservation = Reservation::where('book_id', $bookId)
                ->where('status', Reservation::STATUS_PENDING)
                ->orderBy('reserved_at')
                ->first();

            if ($nextReservation) {
                $nextReservation->update([
                    'status' => Reservation::STATUS_ACTIVE,
                    'expires_at' => now()->addDays(14)
                ]);

                $book->decrement('available_copies');

                if ($book->available_copies <= 0) {
                    $book->update(['status' => Book::STATUS_RESERVED]);
                }

                return $nextReservation;
            }
        }

        return null;
    }

    /**
     * Ensure user owns the reservation.
     */
    private function ensureUserOwnsReservation(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
