<?php

use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login', [\App\Http\Controllers\API\V1\admin\AuthController::class, 'login']);
Route::post('logout', [\App\Http\Controllers\API\V1\admin\AuthController::class, 'logout']);
Route::post('change-password', [\App\Http\Controllers\API\V1\admin\AuthController::class, 'changePassword']);
//Route::get('member/{group_username}', [web\MembersController::class, 'getMember']);

Route::get('counters', [\App\Http\Controllers\API\V1\admin\MembersController::class, 'getCounters']);
Route::get('total-amount', [\App\Http\Controllers\API\V1\admin\PaymentStatisticController::class, 'getStudentAmount']);
Route::get('each-amount', [\App\Http\Controllers\API\V1\admin\PaymentStatisticController::class, 'getEachAmount']);

//Route::post('pay', [\App\Http\Controllers\API\PayController::class, 'sendPay']);
//Route::post('pay-confirm', [\App\Http\Controllers\API\PayController::class, 'payConfirm']);
