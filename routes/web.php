<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('goals', fn() => Inertia::render('Goal'))->name('goals');
    Route::get('habits', fn() => Inertia::render('Habit'))->name('habits');
});

require __DIR__.'/settings.php';
