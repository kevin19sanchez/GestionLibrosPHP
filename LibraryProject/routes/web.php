<?php

use App\Http\Controllers\AutorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('/dash');
});


Route::get('/books', [LibroController::class, 'index'])->name('libro.index');
Route::get('/authors', [AutorController::class, 'index'])->name('autor.index');
Route::get('/categorys', [CategoriaController::class, 'index'])->name('category.index');
Route::get('/loans', [PrestamoController::class, 'index'])->name('prestamo.index');
Route::get('/users', [UsuarioController::class, 'index'])->name('users.index');

///Rutas Libros
Route::post('/bookscreate', [LibroController::class, 'store'])->name('books.create');
Route::put('/bookupdate/{id}', [LibroController::class, 'update'])->name('book.update');
Route::delete('booksdelete/{id}', [LibroController::class, 'destroy'])->name('book.delete');

///Ruta Autores
Route::post('/authorscreate', [AutorController::class, 'store'])->name('author.create');
Route::put('/authorupdate/{id}', [AutorController::class, 'update'])->name('author.update');
Route::delete('/authordelete/{id}', [AutorController::class, 'destroy'])->name('author.delete');

///Rutas Categorias
Route::post('/categorycreate', [CategoriaController::class, 'store'])->name('category.create');
Route::put('/categoryupdate/{id}', [CategoriaController::class, 'update'])->name('category.update');
Route::delete('/categorydelete/{id}', [CategoriaController::class, 'destroy'])->name('category.delete');

///Rutas Pretamos
Route::post('/loanscreate', [PrestamoController::class, 'store'])->name('loans.create');
Route::put('/prestamos/{prestamo}/devolver', [PrestamoController::class, 'devolver'])->name('loans.devolver');

///Rutas Usuarios
Route::post('/userscreate', [UsuarioController::class, 'store'])->name('users.create');
Route::put('/usersupdate/{id}', [UsuarioController::class, 'update'])->name('users.update');
Route::delete('/usersdelete/{id}', [UsuarioController::class, 'destroy'])->name('users.delete');

///Ruta Dash
Route::get('/dash', [DashboardController::class, 'index'])->name('dash.index');


