<?php

namespace App\Http\Controllers\admin;

use App\Models\Book;
use App\Models\BookView;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewBookNotification;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookDeletedNotification;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;

        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('books', 'public');
            $book->pdf_path = $pdfPath;
        }

        $book->save();

        User::all()->each(function ($user) use ($book) {
            $user->notify(new NewBookNotification($book->title));
        });

        return redirect()->route('admin.books.index')->with('success', 'Book has been added and users have been notified.');
    }

    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $book->update($request->all());

        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully');
    }

    public function show(Book $book)
    {
        if (Auth::check()) {
            BookView::firstOrCreate([
                'user_id' => Auth::id(),
                'book_id' => $book->id
            ]);
        }

        return view(Auth::user()->role === 'admin' ? 'admin.books.show' : 'admin.books.show', compact('book'));
    }

    public function destroy(Book $book)
    {
        $bookTitle = $book->title;
        $book->delete();

        User::chunk(100, function ($users) use ($bookTitle) {
            foreach ($users as $user) {
                $user->notify(new BookDeletedNotification($bookTitle));
            }
        });

        return redirect()->route('admin.books.index')->with('success', 'Book has been deleted and users have been notified.');
    }

    public function downloadPdf(Book $book)
    {
        if (!$book->pdf_path) {
            return redirect()->route('admin.books.index')->with('error', 'PDF file was not found.');
        }

        $pdfPath = storage_path('app/public/' . $book->pdf_path);

        if (!file_exists($pdfPath)) {
            return redirect()->route('admin.books.index')->with('error', 'File was not found.');
        }

        return response()->download($pdfPath);
    }

    public function bookViews()
    {
        $views = BookView::with(['user', 'book'])->latest()->get();
        return view('admin.books.views', compact('views'));
    }
}
