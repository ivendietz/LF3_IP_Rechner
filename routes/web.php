<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/ip4', 'ipRechnern')
    ->middleware(['auth', 'verified'])
    ->name('ipRechnern');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
