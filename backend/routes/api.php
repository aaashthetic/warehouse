<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\SaleController;

Route::get('/products', [ProductController::class, 'getProducts']);
Route::post('/products', [ProductController::class, 'addProduct']);

Route::get('/rfid-logs', [LogController::class, 'getLogs']);
Route::post('/rfid-scan', [LogController::class, 'scanBarcode']);

Route::get('/inventory-alerts', [AlertController::class, 'getAlerts']);
Route::post('/sensor-alert', [AlertController::class, 'sensorAlert']);

Route::get('/sales', [SaleController::class, 'getSales']);
Route::post('/predict-demand', [SaleController::class, 'predictDemand']);
