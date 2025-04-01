<?php

use App\Http\Controllers\admin\BooksController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\admin\BooksController as AdminBookController;
use App\Http\Controllers\user\BooksController as UserBookController;
use App\Http\Controllers\admin\UserController as AdminUserController;
use App\Http\Controllers\admin\BorrowingController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('books', AdminBookController::class);
    Route::post('books/{id}/toggle', [AdminBookController::class, 'toggleActive'])->name('books.toggleActive');

    Route::resource('borrowings', BorrowingController::class);
});
Route::post('/books/{book}/borrow', [UserBookController::class, 'borrow'])->name('user.books.borrow');
Route::prefix('user')->name('user.')->group(function () {
    Route::get('books', [UserBookController::class, 'index'])->name('books.index');
});
Route::get('/books', [UserBookController::class, 'index'])->name('books.index');
Route::get('/books/{book}/borrow', [UserBookController::class, 'borrow'])->name('books.borrow');
Route::get('/admin/books/{book}/download', [AdminBookController::class, 'downloadPdf'])->name('admin.books.download');
Route::get('/books/{book}/download', [UserBookController::class, 'downloadPdf'])->name('books.download');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/book-views', [AdminController::class, 'bookViews'])->name('admin.book.views');
});

Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Аватар
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
});

require __DIR__.'/auth.php';
