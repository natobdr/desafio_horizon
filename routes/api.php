<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AeroportoApiController;
use App\Http\Controllers\Api\VooApiController;
use App\Http\Controllers\Api\ClasseApiController;
use App\Http\Controllers\Api\HomeApiController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/aeroportos', [AeroportoApiController::class, 'index'])->name('aeroportos.index');
Route::get('/voos', [VooApiController::class, 'index'])->name('voos.index');
Route::post('/voos/{id}', [VooApiController::class, 'voos'])->name('voos');
Route::post('/voos_classe/{id}', [VooApiController::class, 'voosClasse'])->name('voos.classe');
Route::post('/voos/store', [VooApiController::class, 'store'])->name('voos.store');
Route::post('/voos/update', [VooApiController::class, 'update'])->name('voos.update');
Route::get('/classes', [ClasseApiController::class, 'index'])->name('classes.index');
Route::get('/searchFlights', [VooApiController::class, 'searchFlights'])->name('searchFlights');
Route::post('/registerCustomer', [VooApiController::class, 'registerCustomer'])->name('registerCustomer');
