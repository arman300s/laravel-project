<?php

namespace App\Http\Controllers\admin;

use App\Models\Book;
use App\Models\User; // Добавляем правильный импорт модели User
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewBookNotification;
use App\Notifications\BookDeletedNotification;

class BooksController extends Controller
{
    // Показать все книги
    public function index()
    {
        $books = Book::paginate(10);
        return view('admin.books.index', compact('books'));
    }

    // Показать форму добавления новой книги
    public function create()
    {
        return view('admin.books.create');
    }

    // Сохранить новую книгу
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:5120', // До 5MB,
        ]);

        // Создание книги
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;

        // Проверка загрузки PDF-файла
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('books', 'public');
            $book->pdf_path = $pdfPath;
        }

        $book->save(); // Сохраняем книгу в БД

        // Отправка уведомления всем пользователям
        $bookTitle = $request->title;
        User::all()->each(function ($user) use ($bookTitle) {
            $user->notify(new NewBookNotification($bookTitle));
        });

        return redirect()->route('admin.books.index')->with('success', 'book has been added and users have been notified.');
    }

    // Показать форму редактирования книги
    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    // Обновить книгу
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

    // Показать информацию о книге
    public function show(Book $book)
    {
        return view('admin.books.show', compact('book'));
    }

    // Удалить книгу
    public function destroy(Book $book)
    {
        $bookTitle = $book->title;

        // Удаляем книгу
        $book->delete();

        // Уведомляем всех пользователей
        User::chunk(100, function ($users) use ($bookTitle) {
            foreach ($users as $user) {
                $user->notify(new BookDeletedNotification($bookTitle));
            }
        });

        return redirect()->route('admin.books.index')->with('success', 'book has been deleted and users have been notified.');
    }
    public function downloadPdf(Book $book)
    {
        if (!$book->pdf_path) {
            return redirect()->route('user.books.index')->with('error', 'PDF file was not found.');
        }

        $pdfPath = storage_path('app/public/' . $book->pdf_path);

        if (!file_exists($pdfPath)) {
            return redirect()->route('user.books.index')->with('error', 'File was not found.');
        }

        return response()->download($pdfPath);
    }
}
