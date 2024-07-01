<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AeroportoController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\VooController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Web\ClasseController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\QrCodeController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/trecho',  [HomeController::class, 'trecho'])->name('trecho');
Route::post('/searchflights', [HomeController::class, 'searchFlights'])->name('searchFlights');
Route::post('/registerCustomer', [HomeController::class, 'registerCustomer'])->name('registerCustomer');
Route::post('/gerarticket', [HomeController::class, 'gerarticket'])->name('gerarticket');
Route::get('/searchticket', [HomeController::class, 'searchticket'])->name('searchticket');
Route::get('/buyticket', [HomeController::class, 'buyticket'])->name('buyticket');
Route::get('qrcode/{text}', [QrCodeController::class, 'generateQrCode']);
Route::get('/download-voucher/{id}', [TicketController::class, 'downloadVoucher'])->name('download.voucher');
Route::get('/download-tag/{id}', [TicketController::class, 'downloadTag'])->name('download.tag');
Route::get('/home', [HomeController::class, 'index'])->name('compra-passagem.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/aeroportos', [AeroportoController::class, 'index'])->name('aeroportos.index');
    Route::get('/aeroportos/{id}', [AeroportoController::class, 'show'])->name('view.aeroportos.show');

    Route::get('/classes', [ClasseController::class, 'index'])->name('classes.index');
    Route::get('/classes/create', [ClasseController::class, 'create'])->name('classes.create');
    Route::post('/classes/store', [ClasseController::class, 'store'])->name('classes.store');
    Route::post('/classes/{id}', [ClasseController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [ClasseController::class, 'destroy'])->name('classes.destroy');

    Route::get('/voos', [VooController::class, 'index'])->name('voos.index');
    Route::get('/voos/create', [VooController::class, 'create'])->name('voos.create');
    Route::post('/voos/store', [VooController::class, 'store'])->name('voos.store');
    Route::get('/voos/passageiros', [VooController::class, 'passageiros'])->name('voos.passageiros');
    Route::get('/voos/search', [VooController::class, 'voos'])->name('voos.search');
    Route::get('/voos/edit/{id}', [VooController::class, 'edit'])->name('voos.edit');
    Route::put('/voos/{id}', [VooController::class, 'update'])->name('voos.update');
    Route::delete('/voos/{id}', [VooController::class, 'destroy'])->name('voos.destroy');

    Route::get('/teste', [VooController::class, 'teste'])->name('teste');

    Route::get('/passageiros', [VooController::class, 'index'])->name('passageiros.index');
/*    Route::get('/passageiros/create', [VooController::class, 'create'])->name('passageiros.create');
    Route::post('/passageiros/store', [VooController::class, 'store'])->name('passageiros.store');
    Route::put('/passageiros/{id}', [VooController::class, 'update'])->name('passageiros.update');
    Route::delete('/passageiros/{id}', [VooController::class, 'destroy'])->name('passageiros.destroy');*/
});

require __DIR__.'/auth.php';
