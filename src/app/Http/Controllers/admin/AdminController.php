<?php

namespace app\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BookView;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
    public function bookViews()
    {
        $views = BookView::with(['user', 'book'])->latest()->get();
        return view('admin.books.views', compact('views'));
    }
    public function dashboard()
    {
        return view('admin.dashboard', [
            'categories' => Category::count(),
            'borrowings' => Borrowing::count(),
            'reservations' => Reservation::count(),
        ]);
    }
}
