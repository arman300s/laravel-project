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
use App\Http\Controllers\admin\AdminProfileController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\User\ReservationController as UserReservationController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Books
    Route::resource('books', AdminBookController::class);
    Route::post('books/{id}/toggle', [AdminBookController::class, 'toggleActive'])->name('books.toggleActive');
    Route::get('books/{book}/download', [AdminBookController::class, 'downloadPdf'])->name('books.download');

    // Users
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Book Views
    Route::get('book-views', [AdminController::class, 'bookViews'])->name('book.views');

    // Reservations
    Route::resource('reservations', AdminReservationController::class);
});

// User Routes
Route::middleware('auth')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

        // Books
        Route::get('books', [UserBookController::class, 'index'])->name('books.index');
        Route::get('books/{book}/download', [UserBookController::class, 'downloadPdf'])->name('books.download');

        // Reservations
        Route::resource('reservations', UserReservationController::class)->only([
            'index', 'create', 'store', 'show'
        ]);
        Route::post('reservations/{reservation}/cancel', [UserReservationController::class, 'cancel'])
            ->name('reservations.cancel');
    });

    // Profile Routes (not prefixed)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
});

require __DIR__.'/auth.php';
