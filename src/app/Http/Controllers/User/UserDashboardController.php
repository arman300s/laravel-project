<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reservation;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $stats = [
            'activeBorrowings' => Borrowing::where('user_id', $user->id)
                ->whereNull('returned_at')
                ->count(),
            'pendingReservations' => Reservation::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'overdueBorrowings' => Borrowing::where('user_id', $user->id)
                ->whereNull('returned_at')
                ->where('due_at', '<', now())
                ->count(),
            'totalBorrowings' => Borrowing::where('user_id', $user->id)->count(),
            'totalReservations' => Reservation::where('user_id', $user->id)->count(),
        ];

        $recentBorrowings = Borrowing::with('book')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentReservations = Reservation::with('book')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recommendedBooks = Book::inRandomOrder()
            ->where('status', 'available')
            ->limit(4)
            ->get();

        return view('user.dashboard', compact(
            'stats',
            'recentBorrowings',
            'recentReservations',
            'recommendedBooks'
        ));
    }
}
