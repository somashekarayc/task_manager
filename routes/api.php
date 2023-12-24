<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->controller(AuthController::class)->group(function(){
    Route::post('/sign-up', 'signUp');
    Route::post('/sign-in', 'signIn');
    Route::middleware('auth:sanctum')->post('/sign-out', 'signOut');
});

