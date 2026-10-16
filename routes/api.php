<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\VentaController;
use Illuminate\Support\Facades\Route;

// --- Público (catálogo, sin login) ---
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{producto}', [ProductoController::class, 'show']);
Route::post('/clientes', [ClienteController::class, 'store']); // registro al comprar
Route::post('/ventas', [VentaController::class, 'store']);      // confirmar pedido/venta

// --- Login ---
Route::post('/login', [AuthController::class, 'login']);

// --- Panel interno (requiere token Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/productos', [ProductoController::class, 'store']);
    Route::put('/productos/{producto}', [ProductoController::class, 'update']);
    Route::patch('/productos/{producto}/ajustar-stock', [ProductoController::class, 'ajustarStock']);
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy']);

    Route::get('/clientes', [ClienteController::class, 'index']);
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show']);
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update']);

    Route::get('/ventas', [VentaController::class, 'index']);
    Route::get('/ventas/{venta}', [VentaController::class, 'show']);

    Route::get('/pedidos', [PedidoController::class, 'index']);
    Route::post('/pedidos', [PedidoController::class, 'store']);
    Route::put('/pedidos/{pedido}', [PedidoController::class, 'update']);
});
