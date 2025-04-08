<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\ReservationActivated;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations for users.
     */
    public function userIndex()
    {
        $reservations = auth()->user()->reservations()
            ->with('book')
            ->orderBy('status')
            ->orderBy('reserved_at', 'desc')
            ->paginate(10);

        return view('user.reservations.index', compact('reservations'));
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
     * Show the form for creating a new reservation (for users).
     */
    public function userCreate()
    {
        $books = Book::where('status', '!=', Book::STATUS_UNAVAILABLE)->get();
        return view('user.reservations.create', compact('books'));
    }

    /**
     * Store a newly created reservation in storage (for users).
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

            // Запрещаем резервирование для специальных статусов
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
    public function adminIndex()
    {
        $reservations = Reservation::with(['user', 'book'])
            ->orderBy('status')
            ->orderBy('reserved_at', 'desc')
            ->paginate(10);

        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new reservation (for admin).
     */
    public function adminCreate()
    {
        $books = Book::all();
        $users = User::all();
        return view('admin.reservations.create', compact('books', 'users'));
    }

    /**
     * Store a newly created reservation in storage (for admin).
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
     * Update the specified reservation in storage.
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
     * Remove the specified reservation from storage.
     */
    public function adminDestroy(Reservation $reservation)
    {
        return DB::transaction(function () use ($reservation) {
            if (in_array($reservation->status, ['active', 'completed'])) {
                return back()->with('error', 'Cannot delete active or completed reservations.');
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
     * Cancel reservation (for users).
     */
    public function userCancel(Reservation $reservation)
    {
        $this->ensureUserOwnsReservation($reservation);

        return DB::transaction(function () use ($reservation) {
            // Проверяем, можно ли отменить эту резервацию
            if (!$reservation->canBeCanceled()) {
                return back()->with('error', 'This reservation cannot be canceled.');
            }

            $book = $reservation->book;
            $wasActive = $reservation->isActive;

            $reservation->update([
                'status' => Reservation::STATUS_CANCELED,
                'expires_at' => now(),
                'canceled_at' => now(), // Добавляем время отмены
            ]);

            // Если была активной - освобождаем книгу
            if ($wasActive) {
                $book->increment('available_copies');

                // Обновляем статус книги если нужно
                if ($book->available_copies > 0 && $book->status === Book::STATUS_RESERVED) {
                    $book->update(['status' => Book::STATUS_AVAILABLE]);
                }

                // Активируем следующую резервацию
                $this->activateNextPendingReservation($book->id);
            }
            return redirect()->route('user.reservations.index')
                ->with('success', 'Reservation canceled successfully.');
        });
    }

    /**
     * Cancel reservation (for admin).
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
     * Activate the next pending reservation when a book becomes available.
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

                // Send notification to user that their reservation is now active
                $nextReservation->user->notify(new ReservationActivated($nextReservation));

                return $nextReservation;
            }
        }

        return null;
    }
    /**
     * Ensure the authenticated user owns the reservation.
     */
    private function ensureUserOwnsReservation(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Create a borrowing from an active reservation.
     */
    private function createBorrowingFromReservation(Reservation $reservation)
    {
        return DB::transaction(function () use ($reservation) {
            $book = $reservation->book;
            $user = $reservation->user;

            // Check if book is available
            if ($book->available_copies <= 0) {
                return false;
            }

            // Create the borrowing record
            $borrowing = Borrowing::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'due_at' => now()->addDays(14), // Default 2 weeks borrowing period
                'status' => 'active',
                'description' => 'Auto-created from reservation #' . $reservation->id,
            ]);

            // Update book availability
            $book->decrement('available_copies');
            if ($book->available_copies <= 0) {
                $book->update(['status' => Book::STATUS_RESERVED]);
            }

            // Update reservation status
            $reservation->update([
                'status' => Reservation::STATUS_COMPLETED,
                'expires_at' => now()
            ]);

            return $borrowing;
        });
    }
}
