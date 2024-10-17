<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationMail;
use App\Livewire\ActivateAccount;
use App\Models\User;  // Make sure to import the User model

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/activate', ActivateAccount::class)->name('activation-page');



require __DIR__.'/auth.php';
