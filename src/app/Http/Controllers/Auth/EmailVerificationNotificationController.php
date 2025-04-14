<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\UsersSearchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\User\UserBorrowingController;
use App\Http\Controllers\Admin\AdminBorrowingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    })->name('dashboard');
});

Route::prefix('user')->name('user.')->middleware(['auth', 'verified', 'user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/search-book', [BookController::class, 'userSearch'])->name('search-book');
        Route::get('/', [BookController::class, 'userIndex'])->name('index');
        Route::get('/{book}', [BookController::class, 'userShow'])->name('show');
    });

    Route::prefix('borrowings')->name('borrowings.')->controller(UserBorrowingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{borrowing}', 'show')->name('show');
        Route::post('/', 'store')->name('store');
        Route::post('/{borrowing}/return', 'return')->name('return');
        Route::delete('/{borrowing}', 'destroy')->name('destroy');
    });

    Route::get('/users/search', [UsersSearchController::class, 'userIndex'])->name('users.search');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
    Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users/search', [UsersSearchController::class, 'adminIndex'])->name('users.search');
    Route::resource('users', AdminUserController::class);

    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/search-book', [BookController::class, 'adminSearch'])->name('search-book');
        Route::get('/', [BookController::class, 'adminIndex'])->name('index');
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::post('/', [BookController::class, 'store'])->name('store');
        Route::get('/{book}', [BookController::class, 'adminShow'])->name('show');
        Route::get('/{book}/edit', [BookController::class, 'edit'])->name('edit');
        Route::put('/{book}', [BookController::class, 'update'])->name('update');
        Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('borrowings')->name('borrowings.')->controller(AdminBorrowingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{borrowing}', 'show')->name('show');
        Route::get('/{borrowing}/edit', 'edit')->name('edit');
        Route::put('/{borrowing}', 'update')->name('update');
        Route::post('/{borrowing}/return', 'return')->name('return');
        Route::delete('/{borrowing}', 'destroy')->name('destroy');
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'adminIndex'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'adminShow'])->name('show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('authors')->name('authors.')->group(function () {
        Route::get('/', [AuthorController::class, 'adminIndex'])->name('index');
        Route::get('/create', [AuthorController::class, 'create'])->name('create');
        Route::post('/', [AuthorController::class, 'store'])->name('store');
        Route::get('/{author}', [AuthorController::class, 'adminShow'])->name('show');
        Route::get('/{author}/edit', [AuthorController::class, 'edit'])->name('edit');
        Route::put('/{author}', [AuthorController::class, 'update'])->name('update');
        Route::delete('/{author}', [AuthorController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::get('/books/{book}/download/{format}', [BookController::class, 'download'])
        ->name('books.download')
        ->where('format', 'pdf|docx|epub');
});
