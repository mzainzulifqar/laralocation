<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/location', [LocationController::class, 'store'])->name('location.store');
    Route::post('/location/fetch-address', [LocationController::class, 'fetchAddress'])->name('location.address');

    Route::get('/rider', [RiderController::class, 'index'])->name('rider.index');
    Route::post('/rider/get-location', [RiderController::class, 'getLocation'])->name('rider.location');
});

Auth::routes();

