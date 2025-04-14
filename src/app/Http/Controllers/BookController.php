<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{

    public function index()
    {
        $search = request('search');

        $books = Book::with(['author', 'category'])
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('author', function($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('category', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->paginate(10);

        return view('user.books.index', compact('books'));
    }



    public function show(Book $book)
    {
        $book->load(['author', 'category']);
        return view('user.books.show', compact('book'));
    }

    public function adminIndex()
    {
        $search = request('search');

        $books = Book::with(['author', 'category'])
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('author', function($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('category', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->paginate(10);

        return view('admin.books.index', compact('books'));
    }


    public function create()
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('admin.books.create', compact('authors', 'categories'));
    }

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
            'file_pdf' => 'nullable|file|mimes:pdf|max:204800',
            'file_docx' => 'nullable|file|mimes:docx|max:204800',
            'file_epub' => 'nullable|file|mimes:epub|max:204800',
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

    public function adminShow(Book $book)
    {
        $book->load(['author', 'category']);
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'authors', 'categories'));
    }

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
