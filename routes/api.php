<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmartHomeController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/smarthome/status', [SmartHomeController::class, 'getStatus']);
Route::post('/smarthome/update', [SmartHomeController::class, 'updateStatus']);
