<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialLoginController;

// Rota para exibir a tela de login
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/', function () {
    return view('login');
})->name('login');

// Rotas para Socialite
Route::get('/auth/google', [SocialLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);

Route::get('/auth/microsoft', [SocialLoginController::class, 'redirectToMicrosoft'])->name('login.microsoft');
Route::get('/auth/microsoft/callback', [SocialLoginController::class, 'handleMicrosoftCallback']);

Route::get('/auth/meta', [SocialLoginController::class, 'redirectToMeta'])->name('login.meta');
Route::get('/auth/meta/callback', [SocialLoginController::class, 'handleMetaCallback']);

