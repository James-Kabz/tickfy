<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// email verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// account activation
Route::get('activate-account/{token}', [RegisteredUserController::class, 'activateAccount'])->name('activate-account');
Route::post('activate-account/{token}', [RegisteredUserController::class, 'setPassword'])->name('set-password');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['role:super-admin|admin'])->group(function () {
    // permissions
    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [PermissionController::class, 'destroy']);

    // roles
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    Route::get('roles/{roleId}/delete', [\App\Http\Controllers\RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);
});

require __DIR__ . '/auth.php';
