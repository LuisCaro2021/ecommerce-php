<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductApiController;
use App\Http\Controllers\PedidoController;
use App\Models\Product;
use App\Models\Pedido;
use App\Models\PedidoProduct;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('product', [ProductApiController::class, 'index']);
Route::get('product/{id}', [ProductApiController::class, 'getById']);

Route::post('oauth/token', 'Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::post('product', [ProductApiController::class, 'store']);

Route::get('pedido', [PedidoController::class, 'index']);

Route::post('pedido', [PedidoController::class, 'create']);

Route::get('pedido/{email}', [PedidoController::class, 'getById']);

