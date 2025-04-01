<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Book;
class BorrowingController extends Controller
{
    public function index()
    {

        $borrowings = Borrowing::with(['user', 'book'])->paginate(10);
        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $users = User::all();  // Получаем всех пользователей
        $books = Book::all();  // Получаем все книги
        return view('admin.borrowings.create', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        Borrowing::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrowed_at' => $request->borrowed_at,
            'due_date' => $request->due_date,
            'returned_at' => $request->returned_at,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.borrowings.index')->with('success', 'Заимствование добавлено');
    }

    public function show(Borrowing $borrowing)
    {
        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function edit($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $users = User::all();
        $books = Book::all();

        return view('admin.borrowings.edit', compact('borrowing', 'users', 'books'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $borrowing->update($request->all());
        return redirect()->route('admin.borrowings.index')->with('success', 'Заимствование обновлено');
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();
        return redirect()->route('admin.borrowings.index')->with('success', 'Заимствование удалено');
    }
}
