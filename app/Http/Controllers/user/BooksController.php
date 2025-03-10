<?php

namespace App\Http\Controllers\user;

use App\Models\Book;
use App\Http\Controllers\Controller;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('user.books.index', compact('books'));
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
}
