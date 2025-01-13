<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TicketTypesController;
use App\Http\Controllers\UserController;
use App\Models\Event;
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
// Route::get('activate-account/{token}', [RegisteredUserController::class, 'activateAccount'])->name('activate-account');
// Route::post('activate-account/{token}', [RegisteredUserController::class, 'setPassword'])->name('set-password');


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

    Route::resource('users', UserController::class);
    Route::get('users/{userId}/delete', [UserController::class, 'destroy']);


    // events
    Route::resource('events', \App\Http\Controllers\EventsController::class);
    Route::get('events/{eventId}/delete', [\App\Http\Controllers\EventsController::class, 'destroy']);
    Route::get('events/{jobId}', [EventsController::class, 'show'])->name('events.show');
    Route::get('events.search', [EventsController::class, 'edit'])->name('events.search');
    Route::get('/events/{event}/ticket-types/create', [TicketTypesController::class, 'create'])->name('events.ticket-types.create');
    Route::post('/events/{event}/ticket-types', [TicketTypesController::class, 'store'])->name('events.ticket-types.store');



    // ticket types
    Route::resource('ticket-types', TicketTypesController::class);
    Route::get('ticket-types/{ticketType}/delete', [\App\Http\Controllers\TicketTypesController::class, 'destroy']);

});

require __DIR__ . '/auth.php';
