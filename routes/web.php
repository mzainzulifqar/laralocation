<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RidersController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MedicineController;
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

    Route::get('/rider', [RidersController::class, 'index'])->name('rider.index');
    Route::post('/rider/get-location', [RidersController::class, 'getLocation'])->name('rider.location');
    Route::post('/rider/get-route', [RidersController::class, 'getRoute'])->name('rider.route');

    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');

    Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
    Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store');
    Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
});

Auth::routes();

