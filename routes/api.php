<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllowancesApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::middleware('auth:api')->group(function () {
    Route::get('/allowances', [AllowancesApiController::class, 'index']);
    Route::get('/allowances/{id}', [AllowancesApiController::class, 'show']);
    Route::get('/allowances/user/{id}', [AllowancesApiController::class, 'showUserAllowances']);
    Route::post('/allowances', [AllowancesApiController::class, 'store']);
    Route::put('/allowances/{id}', [AllowancesApiController::class, 'update']);
    Route::delete('/allowances/{id}', [AllowancesApiController::class, 'destroy']);
//});