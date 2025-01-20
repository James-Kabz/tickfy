<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TicketTypesController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
// return view('events.index');
// });
// Public Routes
Route::get('/', [EventsController::class, 'welcome'])->name('welcome'); // Public welcome page
Route::get('events', [EventsController::class, 'index'])->name('events.index'); // Public list of events
Route::get('events/{event}', [EventsController::class, 'show'])->name('events.show'); // Public view of a single event\
Route::post('/events/{event}/tickets', [TicketsController::class, 'store'])->name('tickets.store');
// Search events
Route::get('events.search', [EventsController::class, 'search'])->name('events.search'); // Fix route
// Email Verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Account Activation
Route::get('activate-account/{token}', [RegisteredUserController::class, 'activateAccount'])->name('activate-account');
Route::post('activate-account/{token}', [RegisteredUserController::class, 'setPassword'])->name('set-password');

// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin and Super-Admin Routes
Route::middleware(['role:super-admin|admin'])->group(function () {
    // Permissions
    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [PermissionController::class, 'destroy']);

    // Roles
    Route::resource('roles', RoleController::class);
    Route::get('roles/{roleId}/delete', [RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);

    // Users
    Route::resource('users', UserController::class);
    Route::get('users/{userId}/delete', [UserController::class, 'destroy']);

    // Events
    Route::get('events', [EventsController::class, 'index'])->name('events.index');
    Route::get('events/{eventId}/delete', [\App\Http\Controllers\EventsController::class, 'destroy']);
    Route::get('events/{event}/edit', [EventsController::class, 'edit'])->name('events.edit');
    Route::get('events.create', [EventsController::class, 'create'])->name('events.create');
    // Search events
    Route::get('events.search', [EventsController::class, 'search'])->name('events.search'); // Fix route
    // Ticket Types
    Route::get('/events/{event}/ticket-types/create', [TicketTypesController::class, 'create'])->name('events.ticket-types.create');
    Route::post('/events/{event}/ticket-types', [TicketTypesController::class, 'store'])->name('events.ticket-types.store');

    // Tickets
    // Route::post('/events/{event}/tickets', [TicketsController::class, 'store'])->name('tickets.store');
    Route::get('tickets', [TicketsController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{event}', [TicketsController::class, 'show'])->name('tickets.show'); // Show tickets for a specific event

    // Ticket Types Management
    Route::resource('ticket-types', TicketTypesController::class);
    Route::get('ticket-types/{ticketType}/delete', [TicketTypesController::class, 'destroy']);
});

Route::post('/payment', [PaymentController::class, 'initiatePayment'])->name('payment.initiatePayment');
Route::get('/token', [PaymentController::class, 'token'])->name('token');
Route::get('/payments/initiateStkPush', [PaymentController::class, 'initiateStkPush'])->name('payments.initiateStkPush');
// Route::post('/payments/initiateStkPush', [PaymentController::class, 'handleStkCallback'])->name('payments.stkCallback');
Route::post('/payments/stkcallback', [PaymentController::class, 'stkCallback'])->name('payments.stkcallback');

Route::get('/payment/{event}', [PaymentController::class, 'show'])->name('payment.show');
Route::get('/payments/status', [PaymentController::class, 'paymentStatus'])->name('ticket.status');

Route::get('/ticket/confirmation/{ticket_id}', [TicketsController::class, 'view'])->name('ticket.confirmation');


require __DIR__ . '/auth.php';
