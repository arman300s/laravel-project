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
        ]);

        Book::create($request->all());

        // Отправляем уведомление всем пользователям
        $bookTitle = $request->title;

        // Отправляем уведомление всем пользователям
        User::all()->each(function ($user) use ($bookTitle) {
            $user->notify(new NewBookNotification($bookTitle));
        });

        return redirect()->route('admin.books.index')->with('success', 'Книга добавлена и пользователи уведомлены.');
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

        return redirect()->route('admin.books.index')->with('success', 'Книга удалена и пользователи уведомлены.');
    }
}
