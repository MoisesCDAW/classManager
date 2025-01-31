<?php

use App\Livewire\UserManagerComponent;
use Illuminate\Support\Facades\Route;

Route::redirect("/", "dashboard");

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware('auth')
    ->name('profile');

Route::get('profesores', UserManagerComponent::class)
    ->middleware('auth')
    ->name('userManager');


require __DIR__.'/auth.php';
