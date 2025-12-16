<?php

use App\Http\Controllers\Admin\emailController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Group for authenticated users
Route::middleware(['auth', 'verified'])->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.admin_dashboard');
    })->middleware(['rolemanager:admin.dashboard'])->name('admin.dashboard');

    // Publisher Dashboard
    Route::get('/publisher/dashboard', function () {
        return view('publisher.publisher_dashboard');
    })->middleware(['rolemanager:publisher.dashboard'])->name('publisher.dashboard');
});
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'rolemanager:admin.dashboard'])
    ->group(function () {

        Route::resource('user_management', UserController::class);
        Route::resource('place_management', PlaceController::class);

        Route::get(
            'place_management/{place}/images',
            [PlaceController::class, 'images']
        )->name('place_management.images');
    });


require __DIR__ . '/auth.php';
