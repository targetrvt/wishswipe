<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Api\NegotiateRequestController;
use Illuminate\Http\Request;

// Home page (landing page)
Route::get('/', function () {
    return view('landing'); // This will use the landing.blade.php view
});

// Redirect /home to / 
Route::redirect('/home', '/');
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// API Routes for Negotiate Requests
Route::middleware(['auth'])->group(function () {
    Route::apiResource('negotiate-requests', NegotiateRequestController::class);
    Route::post('negotiate-requests/{negotiateRequest}/accept', [NegotiateRequestController::class, 'accept']);
    Route::post('negotiate-requests/{negotiateRequest}/decline', [NegotiateRequestController::class, 'decline']);
    Route::post('negotiate-requests/{negotiateRequest}/counter-offer', [NegotiateRequestController::class, 'counterOffer']);
});


// (Removed) Breezy 2FA bypass route