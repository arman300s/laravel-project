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
}
