<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AeroportoApiController;
use App\Http\Controllers\Api\VooApiController;
use App\Http\Controllers\Api\ClasseApiController;
use App\Http\Controllers\Api\PassageiroController;
use App\Http\Controllers\Api\TicketAPiController;
use App\Http\Controllers\Api\ApiController;

/*Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/login', [ApiController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/aeroportos', [AeroportoApiController::class, 'index'])->name('aeroportos.index');
    Route::get('/voos', [VooApiController::class, 'index'])->name('voos.index');
    Route::get('/voos/{id}', [VooApiController::class, 'voos'])->name('voos');
    Route::post('/voos/calcelar', [VooApiController::class, 'destroy'])->name('voos');
    Route::post('/voos_classe/{id}', [VooApiController::class, 'voosClasse'])->name('voos.classe');
    Route::post('/voos/store', [VooApiController::class, 'store'])->name('voos.store');
    Route::put('/voos/update', [VooApiController::class, 'update'])->name('voos.update');
    Route::post('/voos/search', [VooApiController::class, 'search'])->name('voos.search');

    Route::get('/passageiros/voo', [PassageiroController::class, 'passageirosVoo'])->name('passageiros.voo');

    Route::post('/ticket/getpassage', [TicketAPiController::class, 'getPassage'])->name('get.Passage');
    Route::post('/ticket/destroy', [TicketAPiController::class, 'destroy'])->name('ticket.destroy');

    Route::get('/classes', [ClasseApiController::class, 'index'])->name('classes.index');
    Route::get('/searchFlights', [VooApiController::class, 'searchFlights'])->name('searchFlights');
    Route::post('/registerCustomer', [VooApiController::class, 'registerCustomer'])->name('registerCustomer');
});

Route::post('/ticket/buypassage', [TicketAPiController::class, 'registerPassage'])->name('register.Passage');
Route::post('/ticket/downloadTag', [TicketAPiController::class, 'downloadTag'])->name('ticket.downloadTag');
Route::post('/ticket/downloadVoucher', [TicketAPiController::class, 'downloadVoucher'])->name('ticket.downloadVoucher');

