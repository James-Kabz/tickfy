<?php
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TicketTypesController;
use App\Http\Controllers\UserController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
// return view('welcome');
// });
Route::get('/', [EventsController::class, 'welcome'])->name('welcome');


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
Route::resource('roles', RoleController::class);
Route::get('roles/{roleId}/delete', [RoleController::class, 'destroy']);
Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
Route::put('roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);

Route::resource('users', UserController::class);
Route::get('users/{userId}/delete', [UserController::class, 'destroy']);

// events
Route::resource('events', EventsController::class);

Route::get('events/{event}/delete', [EventsController::class, 'destroy']); // Fix route parameter to {event}

// Show event (remove redundant route)
Route::get('events/{event}', [EventsController::class, 'show'])->name('events.show'); // Use route model binding

// Search events
Route::get('events.search', [EventsController::class, 'search'])->name('events.search'); // Fix route

// Event edit
Route::get('events/{event}/edit', [EventsController::class, 'edit'])->name('events.edit');

// Ticket Types
Route::get('/events/{event}/ticket-types/create', [TicketTypesController::class, 'create'])->name('events.ticket-types.create');
Route::post('/events/{event}/ticket-types', [TicketTypesController::class, 'store'])->name('events.ticket-types.store');

// tickets
Route::post('/events/{event}/tickets', [TicketsController::class, 'store'])->name('tickets.store');
Route::get('tickets', [TicketsController::class, 'index'])->name('tickets.index');
Route::get('tickets/{event}', [TicketsController::class, 'show'])->name('tickets.show'); // Use {event} instead of {eventId}

// ticket types
Route::resource('ticket-types', TicketTypesController::class);
Route::get('ticket-types/{ticketType}/delete', [TicketTypesController::class, 'destroy']);
});

require __DIR__ . '/auth.php';