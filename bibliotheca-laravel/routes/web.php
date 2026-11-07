<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Placeholder pages so every nav link works.
use App\Http\Controllers\BookController;

Route::get('/books', [BookController::class, 'index'])->name('books');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

use App\Http\Controllers\BorrowingController;

Route::get('/borrowing', [BorrowingController::class, 'index'])->name('borrowing');
Route::get('/borrowing/create', [BorrowingController::class, 'create'])->name('borrowing.create');
Route::post('/borrowing', [BorrowingController::class, 'store'])->name('borrowing.store');
Route::get('/borrowing/{borrowing}', [BorrowingController::class, 'show'])->name('borrowing.show');

Route::get('/return', function () {
    return view('placeholder', ['title' => 'Return']);
})->name('return');

Route::get('/members', function () {
    return view('placeholder', ['title' => 'Members']);
})->name('members');

Route::get('/categories', function () {
    return view('placeholder', ['title' => 'Categories']);
})->name('categories');

Route::get('/analytics', function () {
    return view('placeholder', ['title' => 'Analytics']);
})->name('analytics');

