<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;

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

Route::get('currency/{day}/{month}/{year}', [CurrencyController::class, 'actionGetByDate']);
Route::get('currency/form', [CurrencyController::class, 'actionForm']);
Route::post('currency/list', [CurrencyController::class, 'actionGetListByDate'])->name('currency/list');
Route::get('currency/list/{id}', [CurrencyController::class, 'actionGetListById']);
