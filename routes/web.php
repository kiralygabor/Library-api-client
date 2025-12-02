<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/users/login', [UsersController::class, 'login']); 

Route::get("/authors", [AuthorController::class, "index"])->name('authors.index');
Route::get("/authors/{id}", [AuthorController::class, "show"])->name('authors.show');
Route::post("/authors/{id}", [AuthorController::class, "edit"])->name('authors.edit');
Route::post('/authors', [AuthorController::class, 'store'])->name('authors.store');
Route::put("/authors/{id}", [AuthorController::class, "update"])->name('authors.update');
Route::delete("/authors/{id}", [AuthorController::class, "destroy"])->name('authors.destroy');

Route::get("/books", [BookController::class, "index"])->name('books.index');
Route::get("/books/{id}", [BookController::class, "show"])->name('books.show');
Route::post("/books/{id}", [BookController::class, "edit"])->name('books.edit');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::put("/books/{id}", [BookController::class, "update"])->name('books.update');
Route::delete("/books/{id}", [BookController::class, "destroy"])->name('books.destroy');

Route::get("/categories", [CategoryController::class, "index"])->name('categories.index');
Route::get("/categories/{id}", [CategoryController::class, "show"])->name('categories.show');
Route::post("/categories/{id}", [CategoryController::class, "edit"])->name('categories.edit');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put("/categories/{id}", [CategoryController::class, "update"])->name('categories.update');
Route::delete("/categories/{id}", [CategoryController::class, "destroy"])->name('categories.destroy');

require __DIR__.'/auth.php';
