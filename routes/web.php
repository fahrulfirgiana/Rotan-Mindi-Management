<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SubkontraktorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('inventaris.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [InventarisController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/beranda', [HomeController::class, 'beranda']);

Route::get('/view_order', [HomeController::class, 'view_order']);
Route::get('/orders', [HomeController::class, 'view_order'])->name('orders.index');
Route::get('/view_addorder', [HomeController::class, 'view_addorder']);
Route::post('add_order', [HomeController::class, 'add_order']);
Route::get('/edit_order/{id}', [HomeController::class, 'edit_order']);
Route::post('/update_order/{id}', [HomeController::class, 'update_order']);
Route::get('/delete_order/{id}', [HomeController::class, 'delete_order']);
Route::get('/showDetail/{id}', [ProductController::class, 'showDetails']);

Route::get('/view_product', [ProductController::class, 'view_product']);
Route::get('/view_addproduct', [ProductController::class, 'view_addproduct']);
Route::post('add_product', [ProductController::class, 'add_product']);
Route::get('/edit_product/{id}', [ProductController::class, 'edit_product']);
Route::post('/update_product/{id}', [ProductController::class, 'update_product']);
Route::get('/delete_product/{id}', [ProductController::class, 'delete_product']);
Route::get('/showDetails/{id}', [InventarisController::class, 'showDetails']);

Route::get('/view_kontraktor', [ProductController::class, 'view_kontraktor']);

Route::get('/show_order', [InventarisController::class, 'show_order'])->name('show.order');
Route::get('/show_order', [InventarisController::class, 'show_order'])->name('orders.show');
Route::get('/edit_pesanan/{id}', [InventarisController::class, 'edit_pesanan']);
Route::post('/update_pesanan/{id}', [InventarisController::class, 'update_pesanan']);
Route::get('/detail_pesanan/{id}', [InventarisController::class, 'detail_pesanan']);
Route::get('/filter_order', [InventarisController::class, 'show_order'])->name('filter_order');
Route::get('/export-pdf', [InventarisController::class, 'exportPDF'])->name('export.pdf');

Route::get('/show_kontraktor', [InventarisController::class, 'show_kontraktor']);
Route::get('/show_subkontraktor', [InventarisController::class, 'show_subkontraktor']);
Route::post('/add_subkontraktor', [InventarisController::class, 'add_subkontraktor']);
Route::get('/edit_sub/{id}', [InventarisController::class, 'edit_sub']);
Route::put('/update_sub/{id}', [InventarisController::class, 'update_sub']);
Route::get('/delete_sub/{id}', [InventarisController::class, 'delete_sub']);


Route::post('/send-message-whatsapp', [InventarisController::class, 'sendMessageToWhatsApp']);



require __DIR__.'/auth.php';

Route::resource('manager/dashboard', HomeController::class)->middleware(['auth','manager']);

