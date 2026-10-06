<?php

use App\Http\Controllers\TiketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TiketController::class, 'pilih'])->name('home');
Route::post('/pesan', [TiketController::class, 'simpan'])->name('pesan.simpan');

Route::get('/pesan/{order}/detail', [TiketController::class, 'detail'])->name('pesan.detail');
Route::post('/pesan/{order}/detail', [TiketController::class, 'simpanDetail'])->name('pesan.detail.simpan');

Route::get('/pesan/{order}/metode', [TiketController::class, 'metode'])->name('pesan.metode');
Route::post('/pesan/{order}/metode', [TiketController::class, 'simpanMetode'])->name('pesan.metode.simpan');

Route::get('/pesan/{order}/bayar', [TiketController::class, 'bayar'])->name('pesan.bayar');
