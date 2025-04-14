<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'totalBooks' => Book::count(),
            'availableBooks' => Book::where('status', 'available')->count(),
            'borrowedBooks' => Borrowing::whereNull('returned_at')->count(),
            'pendingReservations' => Reservation::where('status', 'pending')->count(),
            'totalUsers' => User::count(),
            'newBooksThisMonth' => Book::where('created_at', '>=', now()->subDays(30))->count(),
            'overdueBorrowings' => Borrowing::whereNull('returned_at')
                ->where('due_at', '<', now())
                ->count(),
        ];

        $recentActivities = $this->getRecentActivities();

        return view('admin.dashboard', compact('stats', 'recentActivities'));
    }


    protected function getRecentActivities(): array
    {
        $activities = [];

        $recentBooks = Book::latest()
            ->take(3)
            ->get()
            ->map(function ($book) {
                return [
                    'type' => 'book_added',
                    'title' => $book->title,
                    'created_at' => $book->created_at,
                    'icon' => 'green',
                    'icon_svg' => '<svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>'
                ];
            });

        $recentUsers = User::latest()
            ->take(3)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user_registered',
                    'user_name' => $user->name,
                    'created_at' => $user->created_at,
                    'icon' => 'blue',
                    'icon_svg' => '<svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>'
                ];
            });

        $recentBorrowings = Borrowing::with(['book', 'user'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($borrowing) {
                return [
                    'type' => 'book_borrowed',
                    'title' => $borrowing->book->title ?? 'Unknown Book',
                    'user_name' => $borrowing->user->name ?? 'Unknown User',
                    'created_at' => $borrowing->created_at,
                    'icon' => 'yellow',
                    'icon_svg' => '<svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                </svg>'
                ];
            });
        $recentReturns = Borrowing::with(['book', 'user'])
            ->whereNotNull('returned_at')
            ->latest('returned_at')
            ->take(3)
            ->get()
            ->map(function ($borrowing) {
                return [
                    'type' => 'book_returned',
                    'title' => $borrowing->book->title ?? 'Unknown Book',
                    'user_name' => $borrowing->user->name ?? 'Unknown User',
                    'created_at' => $borrowing->returned_at,
                    'icon' => 'indigo',
                    'icon_svg' => '<svg class="h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>'
                ];
            });
        $activities = collect()
            ->merge($recentBooks)
            ->merge($recentUsers)
            ->merge($recentBorrowings)
            ->merge($recentReturns)
            ->sortByDesc('created_at')
            ->take(5)
            ->values()
            ->all();

        return $activities;
    }
}
