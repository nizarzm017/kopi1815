<?php

use App\Http\Livewire\CetakLabaRugi;
use App\Http\Livewire\Nota;
use Illuminate\Support\Facades\Route;

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

Route::get('penjualan/nota/{penjualan}', Nota::class)->name('nota');
Route::post('cetaklabarugi', CetakLabaRugi::class)->name('laba-rugi');

// Route::redirect('/login', '/admin/login')->name('login');
