<?php
namespace App\Http\Controllers\user;

use App\Models\Book;
use App\Models\Borrowing;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('user.books.index', compact('books'));
    }

    public function borrow($bookId)
    {
        $book = Book::findOrFail($bookId);



        Borrowing::create([
            'user_id' => auth()->user()->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()->route('books.index')->with('success', 'You have successfully borrowed the book!');
    }

    public function downloadPdf(Book $book)
    {
        if (!$book->pdf_path) {
            return redirect()->route('user.books.index')->with('error', 'PDF file was not found..');
        }

        $pdfPath = storage_path('app/public/' . $book->pdf_path);

        if (!file_exists($pdfPath)) {
            return redirect()->route('user.books.index')->with('error', 'file was not found..');
        }

        return response()->download($pdfPath);
    }

    public function show(Book $book)
    {
        return view('admin.books.show', compact('book'));
    }
}
