<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Display a listing of borrowings for users.
     */
    public function userIndex()
    {
        $borrowings = auth()->user()->borrowings()
            ->with('book')
            ->orderBy('status')
            ->orderBy('borrowed_at', 'desc')
            ->paginate(10);

        return view('user.borrowings.index', compact('borrowings'));
    }

    /**
     * Display the specified borrowing for users.
     */
    public function userShow(Borrowing $borrowing)
    {
        $this->ensureUserOwnsBorrowing($borrowing);
        $borrowing->load('book');
        return view('user.borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for creating a new borrowing (for users).
     */
    public function userCreate()
    {
        $books = Book::where('status', Book::STATUS_AVAILABLE)
            ->where('available_copies', '>', 0)
            ->get();
        return view('user.borrowings.create', compact('books'));
    }

    /**
     * Store a newly created borrowing in storage (for users).
     */
    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'due_at' => 'required|date|after:now',
            'description' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated) {
            $book = Book::lockForUpdate()->findOrFail($validated['book_id']);
            $user = auth()->user();

            if ($book->available_copies <= 0) {
                return back()->withErrors(['book_id' => 'No available copies of this book.']);
            }

            $existingBorrowing = Borrowing::where('user_id', $user->id)
                ->where('book_id', $validated['book_id'])
                ->whereIn('status', ['pending', 'active', 'overdue'])
                ->first();

            if ($existingBorrowing) {
                return back()->withErrors(['book_id' => 'You already have this book borrowed.']);
            }

            $borrowingData = [
                'user_id' => $user->id,
                'book_id' => $validated['book_id'],
                'borrowed_at' => now(),
                'due_at' => $validated['due_at'],
                'description' => $validated['description'] ?? null,
                'status' => 'active',
            ];

            $book->decrement('available_copies');

            if ($book->available_copies <= 0) {
                $book->update(['status' => Book::STATUS_RESERVED]);
            }

            // Check if there's an active reservation for this user and book
            $activeReservation = Reservation::where('user_id', $user->id)
                ->where('book_id', $validated['book_id'])
                ->where('status', Reservation::STATUS_ACTIVE)
                ->first();

            if ($activeReservation) {
                // Link the borrowing to the reservation
                $borrowingData['from_reservation_id'] = $activeReservation->id;

                // Optionally update reservation status to completed if you want
                // $activeReservation->update([
                //     'status' => Reservation::STATUS_COMPLETED,
                //     'expires_at' => now()
                // ]);
            }

            Borrowing::create($borrowingData);

            return redirect()->route('user.borrowings.index')
                ->with('success', 'Book borrowed successfully.');
        });
    }
    /**
     * Display a listing of borrowings for admin.
     */
    public function adminIndex()
    {
        $borrowings = Borrowing::with(['user', 'book'])
            ->orderBy('status')
            ->orderBy('borrowed_at', 'desc')
            ->paginate(10);

        return view('admin.borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new borrowing (for admin).
     */
    public function adminCreate()
    {
        $books = Book::where('available_copies', '>', 0)->get();
        $users = User::all();
        return view('admin.borrowings.create', compact('books', 'users'));
    }

    /**
     * Store a newly created borrowing in storage (for admin).
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'due_at' => 'required|date|after:now',
            'status' => 'sometimes|in:pending,active,returned,overdue',
            'description' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated) {
            $book = Book::lockForUpdate()->findOrFail($validated['book_id']);
            $user = User::findOrFail($validated['user_id']);

            $borrowingData = [
                'user_id' => $validated['user_id'],
                'book_id' => $validated['book_id'],
                'borrowed_at' => now(),
                'due_at' => $validated['due_at'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'] ?? 'active',
            ];

            if ($borrowingData['status'] !== 'returned') {
                if ($book->available_copies <= 0) {
                    return back()->withErrors(['book_id' => 'No available copies of this book.']);
                }
                $book->decrement('available_copies');

                if ($book->available_copies <= 0) {
                    $book->update(['status' => Book::STATUS_RESERVED]);
                }

                // Отменяем активную резервацию пользователя для этой книги
                $activeReservation = Reservation::where('user_id', $user->id)
                    ->where('book_id', $validated['book_id'])
                    ->where('status', Reservation::STATUS_ACTIVE)
                    ->first();

                if ($activeReservation) {
                    $activeReservation->update([
                        'status' => Reservation::STATUS_COMPLETED,
                        'expires_at' => now()
                    ]);
                }
            }

            Borrowing::create($borrowingData);

            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Borrowing created successfully.');
        });
    }

    /**
     * Display the specified borrowing for admin.
     */
    public function adminShow(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'book']);
        return view('admin.borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified borrowing.
     */
    public function adminEdit(Borrowing $borrowing)
    {
        $books = Book::all();
        $users = User::all();
        return view('admin.borrowings.edit', compact('borrowing', 'books', 'users'));
    }

    /**
     * Update the specified borrowing in storage.
     */
    public function adminUpdate(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'book_id' => 'sometimes|exists:books,id',
            'borrowed_at' => 'sometimes|date',
            'returned_at' => 'sometimes|date|after:borrowed_at',
            'due_at' => 'sometimes|date|after:borrowed_at',
            'status' => 'sometimes|in:pending,active,returned,overdue',
            'description' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated, $borrowing) {
            $originalStatus = $borrowing->status;
            $newStatus = $validated['status'] ?? $originalStatus;
            $bookChanged = isset($validated['book_id']) && ($validated['book_id'] != $borrowing->book_id);

            if ($bookChanged) {
                $newBook = Book::lockForUpdate()->findOrFail($validated['book_id']);
                $oldBook = $borrowing->book;

                if ($originalStatus !== 'returned') {
                    $oldBook->increment('available_copies');
                    if ($oldBook->available_copies > 0) {
                        $oldBook->update(['status' => Book::STATUS_AVAILABLE]);
                    }
                }

                if ($newStatus !== 'returned') {
                    if ($newBook->available_copies > 0) {
                        $newBook->decrement('available_copies');
                        if ($newBook->available_copies <= 0) {
                            $newBook->update(['status' => Book::STATUS_RESERVED]);
                        }
                    } else {
                        return back()->withErrors(['book_id' => 'No available copies of the new book.']);
                    }
                }
            } else {
                if ($originalStatus !== $newStatus) {
                    $book = $borrowing->book;

                    if ($originalStatus !== 'returned' && $newStatus === 'returned') {
                        $book->increment('available_copies');
                        if ($book->available_copies > 0) {
                            $book->update(['status' => Book::STATUS_AVAILABLE]);
                        }

                        // Активируем следующую резервацию при возврате книги
                        $reservationController = new ReservationController();
                        $reservationController->activateNextPendingReservation($book->id);
                    } elseif ($originalStatus === 'returned' && $newStatus !== 'returned') {
                        if ($book->available_copies > 0) {
                            $book->decrement('available_copies');
                            if ($book->available_copies <= 0) {
                                $book->update(['status' => Book::STATUS_RESERVED]);
                            }
                        } else {
                            return back()->withErrors(['status' => 'No available copies to mark as not returned.']);
                        }
                    }
                }
            }

            $borrowing->update($validated);

            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Borrowing updated successfully.');
        });
    }

    /**
     * Remove the specified borrowing from storage.
     */
    public function adminDestroy(Borrowing $borrowing)
    {
        return DB::transaction(function () use ($borrowing) {
            if (in_array($borrowing->status, ['active', 'overdue'])) {
                return back()->with('error', 'Cannot delete active or overdue borrowings.');
            }

            if ($borrowing->status !== 'returned') {
                $borrowing->book->increment('available_copies');
                if ($borrowing->book->available_copies > 0) {
                    $borrowing->book->update(['status' => Book::STATUS_AVAILABLE]);
                }
            }

            $borrowing->delete();

            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Borrowing deleted successfully.');
        });
    }

    /**
     * Mark borrowing as returned (for users).
     */
    public function userReturn(Borrowing $borrowing)
    {
        $this->ensureUserOwnsBorrowing($borrowing);

        return DB::transaction(function () use ($borrowing) {
            if ($borrowing->status === 'returned') {
                return back()->with('warning', 'Book already returned.');
            }

            $borrowing->update([
                'returned_at' => now(),
                'status' => 'returned'
            ]);

            $book = $borrowing->book;
            $book->increment('available_copies');

            if ($book->available_copies > 0) {
                $book->update(['status' => Book::STATUS_AVAILABLE]);
            }

            // Активируем следующую резервацию при возврате книги
            $reservationController = new ReservationController();
            $reservationController->activateNextPendingReservation($book->id);

            return redirect()->route('user.borrowings.index')
                ->with('success', 'Book returned successfully.');
        });
    }

    /**
     * Mark borrowing as returned (for admin).
     */
    public function adminReturn(Borrowing $borrowing)
    {
        return DB::transaction(function () use ($borrowing) {
            if ($borrowing->status === 'returned') {
                return back()->with('warning', 'Book already returned.');
            }

            $borrowing->update([
                'returned_at' => now(),
                'status' => 'returned'
            ]);

            $book = $borrowing->book;
            $book->increment('available_copies');

            if ($book->available_copies > 0) {
                $book->update(['status' => Book::STATUS_AVAILABLE]);
            }

            // Активируем следующую резервацию при возврате книги
            $reservationController = new ReservationController();
            $reservationController->activateNextPendingReservation($book->id);

            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Book returned successfully.');
        });
    }

    /**
     * Ensure the authenticated user owns the borrowing.
     */
    private function ensureUserOwnsBorrowing(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
