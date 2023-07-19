<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::post('/products/search', [ProductController::class, 'search'])->name('products.search');

Route::get('/indexes', [IndexController::class, 'showIndexes'])->name('indexes.show');

// Route để hiển thị tài liệu của một index
Route::get('/indexes/{index}', [IndexController::class, 'showIndex'])->name('indexes.index');

// Route để hiển thị chi tiết tài liệu từ một index
Route::get('/indexes/{index}/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/indexes/{index}/documents/create', [DocumentController::class, 'create'])->name('documents.create');
Route::post('/indexes/{index}/documents', [DocumentController::class, 'store'])->name('documents.store');
Route::get('/indexes/{index}/documents/{id}', [DocumentController::class, 'show'])->name('documents.show');
Route::get('/indexes/{index}/documents/{id}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
Route::put('/indexes/{index}/documents/{id}', [DocumentController::class, 'update'])->name('documents.update');
Route::delete('/indexes/{index}/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
