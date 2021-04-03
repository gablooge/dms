<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PeraturanController;
use App\Http\Controllers\KategoriJenisDokumenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DokumenController::class, 'index']);
Route::any('dokumen/list', [DokumenController::class, 'getDokumen'])->name('dokumen.list');

Route::get('dokumen/kategori', [KategoriJenisDokumenController::class, 'index']);
Route::any('dokumen/kategori/list', [KategoriJenisDokumenController::class, 'getKategori'])->name('kategori.list');

// Route::resource('peraturan', PeraturanController::class);
Route::get('peraturan', [PeraturanController::class, 'index']);

Route::any('peraturan/list', [PeraturanController::class, 'getPeraturan'])->name('peraturans.list');