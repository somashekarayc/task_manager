<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/auth/sign-up', [AuthController::class, 'signUp']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
