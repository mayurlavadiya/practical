<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
Route::post('/products', [ProductController::class, 'store'])->name('product.store');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/products/{Product_Id}/edit', [ProductController::class, 'edit'])->name('product.edit');
Route::put('/products/{Product_Id}', [ProductController::class, 'update'])->name('product.update');
Route::delete('/products/{Product_Id}', [ProductController::class, 'destroy'])->name('product.destroy');

Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
Route::get('/invoice/{invoiceid}', [InvoiceController::class, 'view'])->name('invoice.view');
Route::get('/invoice/view', [InvoiceController::class, 'view'])->name('invoice.view');

Route::get('/invoice/edit/{id}', [InvoiceController::class, 'edit'])->name('invoice.edit');
Route::put('/invoice/update/{id}', [InvoiceController::class, 'update'])->name('invoice.update'); 
Route::post('/invoice/delete/{id}', [InvoiceController::class, 'destroy'])->name('invoice.delete');
