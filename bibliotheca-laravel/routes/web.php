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
Route::get('/books', function () {
    return view('placeholder', ['title' => 'Books']);
})->name('books');

Route::get('/borrowing', function () {
    return view('placeholder', ['title' => 'Borrowing']);
})->name('borrowing');

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