<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PeraturanController;
use App\Http\Controllers\KategoriJenisDokumenController;
use App\Http\Controllers\JenisDokumenController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SolariumController;

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

// Dokumen
Route::get('/', [DokumenController::class, 'index']);
Route::get('dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
Route::get('dokumen/db', [DokumenController::class, 'db'])->name('dokumen.db');
Route::any('dokumen/datatables', [DokumenController::class, 'datatables'])->name('dokumen.datatables');
Route::any('dokumen/create', [DokumenController::class, 'create'])->name('dokumen.create');
Route::post('dokumen/store', [DokumenController::class, 'store'])->name('dokumen.store');
Route::get('dokumen/{dokumen}/edit/', [DokumenController::class, 'edit'])->name('dokumen.edit');
Route::put('dokumen/{dokumen}', [DokumenController::class, 'update'])->name('dokumen.update');
Route::delete('dokumen/{dokumen}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');
Route::any('dokumen/solr', [DokumenController::class, 'solr'])->name('dokumen.solr');
Route::any('dokumen/database', [DokumenController::class, 'database'])->name('dokumen.database');
// END Dokumen

// Tag 
Route::any('dokumen/tag/select', [TagController::class, 'select'])->name('tag.select');
Route::any('dokumen/tag/datatables', [TagController::class, 'datatables'])->name('tag.datatables');
Route::get('dokumen/tag', [TagController::class, 'index'])->name('tag.index');
Route::any('dokumen/tag/create', [TagController::class, 'create'])->name('tag.create');
Route::post('dokumen/tag/store', [TagController::class, 'store'])->name('tag.store');
Route::get('dokumen/tag/{tag}/edit/', [TagController::class, 'edit'])->name('tag.edit');
Route::put('dokumen/tag/{tag}', [TagController::class, 'update'])->name('tag.update');
Route::delete('dokumen/tag/{tag}', [TagController::class, 'destroy'])->name('tag.destroy');
// END Tag

// Kategori 
Route::any('dokumen/kategori/datatables', [KategoriJenisDokumenController::class, 'datatables'])->name('kategori.datatables');
Route::get('dokumen/kategori', [KategoriJenisDokumenController::class, 'index'])->name('kategori.index');
Route::any('dokumen/kategori/create', [KategoriJenisDokumenController::class, 'create'])->name('kategori.create');
Route::post('dokumen/kategori/store', [KategoriJenisDokumenController::class, 'store'])->name('kategori.store');
Route::get('dokumen/kategori/{kategoriJenisDokumen}/edit/', [KategoriJenisDokumenController::class, 'edit'])->name('kategori.edit');
Route::put('dokumen/kategori/{kategoriJenisDokumen}', [KategoriJenisDokumenController::class, 'update'])->name('kategori.update');
Route::delete('dokumen/kategori/{kategoriJenisDokumen}', [KategoriJenisDokumenController::class, 'destroy'])->name('kategori.destroy');
// END Kategori

// Jenis 
Route::any('dokumen/jenis/datatables', [JenisDokumenController::class, 'datatables'])->name('jenis.datatables');
Route::get('dokumen/jenis', [JenisDokumenController::class, 'index'])->name('jenis.index');
Route::any('dokumen/jenis/create', [JenisDokumenController::class, 'create'])->name('jenis.create');
Route::post('dokumen/jenis/store', [JenisDokumenController::class, 'store'])->name('jenis.store');
Route::get('dokumen/jenis/{jenisDokumen}/edit/', [JenisDokumenController::class, 'edit'])->name('jenis.edit');
Route::put('dokumen/jenis/{jenisDokumen}', [JenisDokumenController::class, 'update'])->name('jenis.update');
Route::delete('dokumen/jenis/{jenisDokumen}', [JenisDokumenController::class, 'destroy'])->name('jenis.destroy');
// END Jenis

Route::get('dokumen/solarium/ping', [SolariumController::class, 'ping'])->name('solarium.ping');


// Route::resource('peraturan', PeraturanController::class);
Route::get('peraturan', [PeraturanController::class, 'index']);
Route::any('peraturan/list', [PeraturanController::class, 'getPeraturan'])->name('peraturans.list');