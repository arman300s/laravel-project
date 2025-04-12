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
    public function userIndex()
    {
        $borrowings = auth()->user()->borrowings()
            ->with('book')
            ->orderBy('status')
            ->orderBy('borrowed_at', 'desc')
            ->paginate(10);

        return view('user.borrowings.index', compact('borrowings'));
    }

    public function userShow(Borrowing $borrowing)
    {
        $this->ensureUserOwnsBorrowing($borrowing);
        $borrowing->load('book');
        return view('user.borrowings.show', compact('borrowing'));
    }

    public function userCreate()
    {
        $books = Book::where('status', Book::STATUS_AVAILABLE)
            ->where('available_copies', '>', 0)
            ->get();
        return view('user.borrowings.create', compact('books'));
    }

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

            $activeReservation = Reservation::where('user_id', $user->id)
                ->where('book_id', $validated['book_id'])
                ->where('status', Reservation::STATUS_ACTIVE)
                ->first();

            $borrowingData = [
                'user_id' => $user->id,
                'book_id' => $validated['book_id'],
                'borrowed_at' => now(),
                'due_at' => $validated['due_at'],
                'description' => $validated['description'] ?? null,
                'status' => Borrowing::STATUS_ACTIVE,
            ];

            if ($activeReservation) {
                $borrowingData['from_reservation_id'] = $activeReservation->id;
                $activeReservation->update([
                    'status' => Reservation::STATUS_COMPLETED,
                    'expires_at' => now()
                ]);
            }

            $book->decrement('available_copies');
            if ($book->available_copies <= 0) {
                $book->update(['status' => Book::STATUS_RESERVED]);
            }

            Borrowing::create($borrowingData);

            return redirect()->route('user.borrowings.index')
                ->with('success', 'Book borrowed successfully.');
        });
    }

    public function adminIndex()
    {
        $borrowings = Borrowing::with(['user', 'book'])
            ->orderBy('status')
            ->orderBy('borrowed_at', 'desc')
            ->paginate(10);

        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function adminCreate()
    {
        $books = Book::where('available_copies', '>', 0)->get();
        $users = User::all();
        return view('admin.borrowings.create', compact('books', 'users'));
    }

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
                'status' => $validated['status'] ?? Borrowing::STATUS_ACTIVE,
            ];

            if ($borrowingData['status'] !== Borrowing::STATUS_RETURNED) {
                if ($book->available_copies <= 0) {
                    return back()->withErrors(['book_id' => 'No available copies of this book.']);
                }
                $book->decrement('available_copies');

                if ($book->available_copies <= 0) {
                    $book->update(['status' => Book::STATUS_RESERVED]);
                }

                $activeReservation = Reservation::where('user_id', $user->id)
                    ->where('book_id', $validated['book_id'])
                    ->where('status', Reservation::STATUS_ACTIVE)
                    ->first();

                if ($activeReservation) {
                    $borrowingData['from_reservation_id'] = $activeReservation->id;
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

    public function adminShow(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'book']);
        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function adminEdit(Borrowing $borrowing)
    {
        $books = Book::all();
        $users = User::all();
        return view('admin.borrowings.edit', compact('borrowing', 'books', 'users'));
    }

    public function adminUpdate(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'book_id' => 'sometimes|exists:books,id',
            'borrowed_at' => 'sometimes|date',
            'returned_at' => 'nullable|date|after_or_equal:borrowed_at',
            'due_at' => 'sometimes|date|after:borrowed_at',
            'status' => 'sometimes|in:'.implode(',', [Borrowing::STATUS_PENDING, Borrowing::STATUS_ACTIVE, Borrowing::STATUS_RETURNED, Borrowing::STATUS_OVERDUE]),
            'description' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated, $borrowing, $request) {
            $originalStatus = $borrowing->status;
            $newStatus = $validated['status'] ?? $originalStatus;
            $originalBookId = $borrowing->book_id;
            $newBookId = $validated['book_id'] ?? $originalBookId;
            $bookChanged = $newBookId != $originalBookId;

            $oldBook = null;
            if ($originalStatus !== Borrowing::STATUS_RETURNED || $bookChanged) {
                $oldBook = Book::lockForUpdate()->find($originalBookId);
            }

            if ($bookChanged) {
                $newBook = Book::lockForUpdate()->findOrFail($newBookId);

                if ($originalStatus !== Borrowing::STATUS_RETURNED && $oldBook) {
                    $oldBook->increment('available_copies');
                    if ($oldBook->available_copies > 0 && $oldBook->status !== Book::STATUS_AVAILABLE) {
                        $oldBook->update(['status' => Book::STATUS_AVAILABLE]);
                    } else {
                        $oldBook->save();
                    }
                    $reservationController = new ReservationController();
                    $reservationController->activateNextPendingReservation($oldBook->id);
                }

                if ($newStatus !== Borrowing::STATUS_RETURNED) {
                    if ($newBook->available_copies > 0) {
                        $newBook->decrement('available_copies');
                        if ($newBook->available_copies <= 0 && $newBook->status !== Book::STATUS_RESERVED) {
                            $newBook->update(['status' => Book::STATUS_RESERVED]);
                        } else {
                            $newBook->save();
                        }
                    } else {
                        return back()->withErrors(['book_id' => 'No available copies of the new book.']);
                    }
                }
            } else {
                if ($originalStatus !== $newStatus) {
                    $book = $oldBook ?? $borrowing->book()->lockForUpdate()->first();

                    if ($originalStatus !== Borrowing::STATUS_RETURNED && $newStatus === Borrowing::STATUS_RETURNED) {
                        $book->increment('available_copies');
                        if ($book->available_copies > 0 && $book->status !== Book::STATUS_AVAILABLE) {
                            $book->update(['status' => Book::STATUS_AVAILABLE]);
                        } else {
                            $book->save();
                        }
                        $reservationController = new ReservationController();
                        $reservationController->activateNextPendingReservation($book->id);
                    } elseif ($originalStatus === Borrowing::STATUS_RETURNED && $newStatus !== Borrowing::STATUS_RETURNED) {
                        if ($book->available_copies > 0) {
                            $book->decrement('available_copies');
                            if ($book->available_copies <= 0 && $book->status !== Book::STATUS_RESERVED) {
                                $book->update(['status' => Book::STATUS_RESERVED]);
                            } else {
                                $book->save();
                            }
                        } else {
                            return back()->withErrors(['status' => 'No available copies to mark as not returned.']);
                        }
                    }
                }
            }

            if ($newStatus === Borrowing::STATUS_RETURNED && empty($validated['returned_at'])) {
                $validated['returned_at'] = now();
            } elseif ($newStatus !== Borrowing::STATUS_RETURNED && $request->has('returned_at')) {
                if (empty($validated['returned_at'])) {
                    $validated['returned_at'] = null;
                }
            } elseif ($newStatus !== Borrowing::STATUS_RETURNED) {
                $validated['returned_at'] = null;
            }

            $borrowing->update($validated);

            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Borrowing updated successfully.');
        });
    }

    public function adminDestroy(Borrowing $borrowing)
    {
        return DB::transaction(function () use ($borrowing) {
            $book = $borrowing->book()->lockForUpdate()->first();
            $originalStatus = $borrowing->status;

            if (in_array($originalStatus, [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_OVERDUE])) {
                return back()->with('error', 'Cannot delete active or overdue borrowings. Please mark as returned first.');
            }
            $borrowing->delete();

            if ($originalStatus !== Borrowing::STATUS_RETURNED && $book) {
                $book->increment('available_copies');
                if ($book->available_copies > 0 && $book->status !== Book::STATUS_AVAILABLE) {
                    $book->update(['status' => Book::STATUS_AVAILABLE]);
                } else {
                    $book->save();
                }
                $reservationController = new ReservationController();
                $reservationController->activateNextPendingReservation($book->id);
            }

            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Borrowing deleted successfully.');
        });
    }
    public function userReturn(Borrowing $borrowing)
    {
        $this->ensureUserOwnsBorrowing($borrowing);

        return DB::transaction(function () use ($borrowing) {
            if ($borrowing->status === Borrowing::STATUS_RETURNED) {
                return back()->with('warning', 'Book already returned.');
            }

            if (!in_array($borrowing->status, [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_OVERDUE])) {
                return back()->with('error', 'Only active or overdue borrowings can be returned.');
            }

            $book = $borrowing->book()->lockForUpdate()->firstOrFail();

            $borrowing->update([
                'returned_at' => now(),
                'status' => Borrowing::STATUS_RETURNED
            ]);

            $book->increment('available_copies');

            if ($book->available_copies > 0 && $book->status !== Book::STATUS_AVAILABLE) {
                $book->update(['status' => Book::STATUS_AVAILABLE]);
            } else {
                $book->save();
            }
            $reservationController = new ReservationController();
            $reservationController->activateNextPendingReservation($book->id);

            return redirect()->route('user.borrowings.index')
                ->with('success', 'Book returned successfully.');
        });
    }

    public function adminReturn(Borrowing $borrowing)
    {
        return DB::transaction(function () use ($borrowing) {
            if ($borrowing->status === Borrowing::STATUS_RETURNED) {
                return back()->with('warning', 'Book already returned.');
            }
            if (!in_array($borrowing->status, [Borrowing::STATUS_ACTIVE, Borrowing::STATUS_OVERDUE])) {
                return back()->with('error', 'Only active or overdue borrowings can be returned.');
            }
            $book = $borrowing->book()->lockForUpdate()->firstOrFail();
            $borrowing->update([
                'returned_at' => now(),
                'status' => Borrowing::STATUS_RETURNED
            ]);
            $book->increment('available_copies');

            if ($book->available_copies > 0 && $book->status !== Book::STATUS_AVAILABLE) {
                $book->update(['status' => Book::STATUS_AVAILABLE]);
            } else {
                $book->save();
            }
            $reservationController = new ReservationController();
            $reservationController->activateNextPendingReservation($book->id);

            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Book returned successfully.');
        });
    }
    private function ensureUserOwnsBorrowing(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
