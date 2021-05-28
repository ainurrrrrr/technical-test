<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/health-check', function () {
    return response()->json('Application is running..', 200);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

// uoms
Route::group([
    'middleware' => 'api'
], function () {
    Route::get('/uoms', [UomController::class, 'getAll']);
    Route::post('/uoms', [UomController::class, 'store']);
    Route::get('/uoms/{uuid}', [UomController::class, 'show']);
    Route::put('/uoms/{uuid}', [UomController::class, 'update']);
    Route::delete('/uoms/{uuid}', [UomController::class, 'destroy']);
});

// customers
Route::group([
    'middleware' => 'api'
], function () {
    Route::get('/customers', [CustomerController::class, 'getAll']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::get('/customers/{uuid}', [CustomerController::class, 'show']);
    Route::put('/customers/{uuid}', [CustomerController::class, 'update']);
    Route::delete('/customers/{uuid}', [CustomerController::class, 'destroy']);
});

// products
Route::group([
    'middleware' => 'api'
], function () {
    Route::get('/products', [ProductController::class, 'getAll']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{uuid}', [ProductController::class, 'show']);
    Route::put('/products/{uuid}', [ProductController::class, 'update']);
    Route::delete('/products/{uuid}', [ProductController::class, 'destroy']);
});

// payment methods
Route::group([
    'middleware' => 'api'
], function () {
    Route::get('/payment-methods', [PaymentMethodController::class, 'getAll']);
    Route::post('/payment-methods', [PaymentMethodController::class, 'store']);
    Route::get('/payment-methods/{uuid}', [PaymentMethodController::class, 'show']);
    Route::put('/payment-methods/{uuid}', [PaymentMethodController::class, 'update']);
    Route::delete('/payment-methods/{uuid}', [PaymentMethodController::class, 'destroy']);
});

// orders
Route::group([
    'middleware' => 'api'
], function () {
    Route::get('/orders', [OrderController::class, 'getAll']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{uuid}', [OrderController::class, 'show']);
    Route::put('/orders/{uuid}', [OrderController::class, 'update']);
    Route::delete('/orders/{uuid}', [OrderController::class, 'destroy']);
});

// payments
Route::group([
    'middleware' => 'api'
], function () {
    Route::put('/payments', [PaymentController::class, 'update']);
});
