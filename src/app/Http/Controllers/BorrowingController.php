<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of borrowings for users.
     */
    public function userIndex()
    {
        $borrowings = auth()->user()->borrowings()->with('book')->paginate(10);
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
        $books = Book::where('available_copies', '>', 0)->get();
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
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['borrowed_at'] = now();
        $validated['status'] = 'pending';

        Borrowing::create($validated);

        return redirect()->route('user.borrowings.index')
            ->with('success', 'Borrowing request submitted successfully.');
    }

    /**
     * Display a listing of borrowings for admin.
     */
    public function adminIndex()
    {
        $borrowings = Borrowing::with(['user', 'book'])->paginate(10);
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
            'description' => 'nullable|string',
        ]);

        $validated['borrowed_at'] = now();
        $validated['status'] = $validated['status'] ?? 'active';

        Borrowing::create($validated);

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Borrowing created successfully.');
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
            'description' => 'nullable|string',
        ]);

        $borrowing->update($validated);

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Borrowing updated successfully.');
    }

    /**
     * Remove the specified borrowing from storage.
     */
    public function adminDestroy(Borrowing $borrowing)
    {
        $borrowing->delete();
        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Borrowing deleted successfully.');
    }

    /**
     * Mark borrowing as returned (for users).
     */
    public function userReturn(Borrowing $borrowing)
    {
        $this->ensureUserOwnsBorrowing($borrowing);

        $borrowing->update([
            'returned_at' => now(),
            'status' => 'returned'
        ]);

        return redirect()->route('user.borrowings.index')
            ->with('success', 'Book returned successfully.');
    }

    /**
     * Mark borrowing as returned (for admin).
     */
    public function adminReturn(Borrowing $borrowing)
    {
        $borrowing->update([
            'returned_at' => now(),
            'status' => 'returned'
        ]);

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Book returned successfully.');
    }

    /**
     * Ensure the authenticated user owns the borrowing.
     */
    private function ensureUserOwnsBorrowing(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
