<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of books for users.
     */
    public function index()
    {
        $books = Book::with(['author', 'category'])->paginate(10);
        return view('user.books.index', compact('books'));
    }

    /**
     * Display the specified book for users.
     */
    public function show(Book $book)
    {
        $book->load(['author', 'category']);
        return view('user.books.show', compact('book'));
    }

    /**
     * Display a listing of books for admin.
     */
    public function adminIndex()
    {
        $books = Book::with(['author', 'category'])->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('admin.books.create', compact('authors', 'categories'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'description' => 'nullable|string',
            'published_year' => 'required|integer|min:1900|max:'.(date('Y')),
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'available_copies' => 'required|integer|min:0',
            'total_copies' => 'required|integer|min:0|gte:available_copies',
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
            'file_docx' => 'nullable|file|mimes:docx|max:20480',
            'file_epub' => 'nullable|file|mimes:epub|max:20480',
        ]);

        $files = ['pdf', 'docx', 'epub'];
        foreach ($files as $format) {
            if ($request->hasFile("file_$format")) {
                $validated["file_$format"] = $request->file("file_$format")->store("books/$format", 'public');
            }
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified book for admin.
     */
    public function adminShow(Book $book)
    {
        $book->load(['author', 'category']);
        return view('admin.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'authors', 'categories'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|unique:books,isbn,'.$book->id,
            'description' => 'nullable|string',
            'published_year' => 'sometimes|integer|min:1900|max:'.(date('Y')),
            'author_id' => 'sometimes|exists:authors,id',
            'category_id' => 'sometimes|exists:categories,id',
            'available_copies' => 'sometimes|integer|min:0',
            'total_copies' => 'sometimes|integer|min:0|gte:available_copies',
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
            'file_docx' => 'nullable|file|mimes:docx|max:20480',
            'file_epub' => 'nullable|file|mimes:epub|max:20480',
        ]);

        $files = ['pdf', 'docx', 'epub'];
        foreach ($files as $format) {
            if ($request->hasFile("file_$format")) {
                if ($book->{"file_$format"}) {
                    Storage::disk('public')->delete($book->{"file_$format"});
                }
                $validated["file_$format"] = $request->file("file_$format")->store("books/$format", 'public');
            }
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        $files = ['pdf', 'docx', 'epub'];
        foreach ($files as $format) {
            if ($book->{"file_$format"}) {
                Storage::disk('public')->delete($book->{"file_$format"});
            }
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }

    /**
     * Download the specified book file.
     */
    public function download(Book $book, string $format)
    {
        if (!in_array($format, ['pdf', 'docx', 'epub'])) {
            abort(404);
        }

        $filePath = $book->{"file_$format"};
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('public')->download($filePath);
    }
}
