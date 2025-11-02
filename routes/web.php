<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', function () {
    return view('admin.admin_dashboard');
})->name('admin.admin_dashboard');


Route::get('/publisher/dashboard', function () {
    return view('publisher.publisher_dashboard');
})->name('publisher.dashboard');

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('Admin')) {
        return redirect()->route('admin.admin_dashboard');
    }

    if ($user->hasRole('publisher')) {
        return redirect()->route('publisher.dashboard');
    }
    return view('home');
})->name('dashboard');

require __DIR__ . '/auth.php';

