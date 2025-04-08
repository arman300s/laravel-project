<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController; // <-- Added
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\UsersSearchController;
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

// User Routes
Route::prefix('user')->name('user.')->middleware(['auth', 'verified', 'user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Book Routes
    Route::prefix('books')->name('books.')->controller(BookController::class)->group(function () {
        Route::get('/search-book', 'userSearch')->name('search-book');
        Route::get('/', 'index')->name('index');
        Route::get('/{book}', 'show')->name('show');
    });

    // Borrowing Routes
    Route::prefix('borrowings')->name('borrowings.')->controller(BorrowingController::class)->group(function () {
        Route::get('/', 'userIndex')->name('index');
        Route::get('/create', 'userCreate')->name('create');
        Route::post('/', 'userStore')->name('store');
        Route::get('/{borrowing}', 'userShow')->name('show');
        Route::post('/{borrowing}/return', 'userReturn')->name('return'); // Note: Might consider PATCH/PUT
    });

    // Reservation Routes <-- Added Section
    Route::prefix('reservations')->name('reservations.')->controller(ReservationController::class)->group(function () {
        Route::get('/', 'userIndex')->name('index');
        Route::get('/create', 'userCreate')->name('create');
        Route::post('/', 'userStore')->name('store');
        Route::get('/{reservation}', 'userShow')->name('show');
        Route::post('/{reservation}/cancel', 'userCancel')->name('cancel');
        Route::post('/admin/reservations/{reservation}/convert-to-borrowing', [ReservationController::class, 'convertToBorrowing'])->name('admin.reservations.convert_to_borrowing');// Note: Might consider PATCH/PUT
    });

    Route::get('/users/search', [UsersSearchController::class, 'userIndex'])->name('users.search');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
    Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users/search', [UsersSearchController::class, 'adminIndex'])->name('users.search');
    Route::resource('users', AdminUserController::class);

    // Book Management
    Route::prefix('books')->name('books.')->controller(BookController::class)->group(function () {
        Route::get('/search-book', 'adminSearch')->name('search-book');
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{book}', 'adminShow')->name('show');
        Route::get('/{book}/edit', 'edit')->name('edit');
        Route::put('/{book}', 'update')->name('update');
        Route::delete('/{book}', 'destroy')->name('destroy');
    });

    // Borrowing Management
    Route::prefix('borrowings')->name('borrowings.')->controller(BorrowingController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/create', 'adminCreate')->name('create');
        Route::post('/', 'adminStore')->name('store');
        Route::get('/{borrowing}', 'adminShow')->name('show');
        Route::get('/{borrowing}/edit', 'adminEdit')->name('edit');
        Route::put('/{borrowing}', 'adminUpdate')->name('update');
        Route::post('/{borrowing}/return', 'adminReturn')->name('return'); // Note: Might consider PATCH/PUT
        Route::delete('/{borrowing}', 'adminDestroy')->name('destroy');
    });

    // Reservation Management <-- Added Section
    Route::prefix('reservations')->name('reservations.')->controller(ReservationController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/create', 'adminCreate')->name('create');
        Route::post('/', 'adminStore')->name('store');
        Route::get('/{reservation}', 'adminShow')->name('show');
        Route::get('/{reservation}/edit', 'adminEdit')->name('edit');
        Route::put('/{reservation}', 'adminUpdate')->name('update');
        Route::delete('/{reservation}', 'adminDestroy')->name('destroy');
        Route::post('/{reservation}/cancel', 'adminCancel')->name('cancel'); // Note: Might consider PATCH/PUT
    });

    // Category Management
    Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{category}', 'adminShow')->name('show');
        Route::get('/{category}/edit', 'edit')->name('edit');
        Route::put('/{category}', 'update')->name('update');
        Route::delete('/{category}', 'destroy')->name('destroy');
    });

    // Author Management
    Route::prefix('authors')->name('authors.')->controller(AuthorController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{author}', 'adminShow')->name('show');
        Route::get('/{author}/edit', 'edit')->name('edit');
        Route::put('/{author}', 'update')->name('update');
        Route::delete('/{author}', 'destroy')->name('destroy');
    });
});

// Common Routes (accessible by both user and admin)
Route::middleware('auth')->group(function () {
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Book Downloads
    Route::get('/books/{book}/download/{format}', [BookController::class, 'download'])
        ->name('books.download')
        ->where('format', 'pdf|docx|epub');
});
