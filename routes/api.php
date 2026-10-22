<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/login', [ApiController::class, 'login']);
Route::post('/upload-sampah', [ApiController::class, 'uploadSampah']);
Route::get('/user-wallet', [ApiController::class, 'getUserWallet']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});