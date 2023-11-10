<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::group(['middleware'=>'auth:sanctum'], function(){
    Route::get('/inventories',[ApiController::class,'getInventories'])->name('getInventories');
    Route::get('/products',[ApiController::class,'getProducts'])->name('getProducts');
    Route::get('/sales',[ApiController::class,'getSales'])->name('getSales');
// });
Route::post('/buattoken',[ApiController::class,'buatToken'])->name('buatToken');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
