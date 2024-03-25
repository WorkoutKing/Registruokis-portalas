<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', [HomeController::class, 'index']);

/* User profile */
Route::get('/profile', [HomeController::class, 'indexUser'])->name('my_profile.indexUser');
Route::get('/my-profile', [ProfileController::class, 'edit'])->name('my-profile.profile');
Route::patch('/my-profile/{user}', [ProfileController::class, 'update'])->name('my-profile.update');

/* Events Routes */
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/my-events', [EventController::class, 'myevents'])->name('events.myevents');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{event}/register', [EventRegistrationController::class, 'create'])->name('event_registration.create');
Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])->name('event_registration.store');
Route::put('/registrations/{registration}/approve', [EventRegistrationController::class, 'approve'])->name('approve_registration');
Route::delete('/registrations/{registration}', [EventRegistrationController::class, 'destroy'])->name('registrations.destroy');
Route::get('/my-registrations', [EventRegistrationController::class, 'myRegistrations'])->name('my_registrations.index');
Route::get('/my-registrations/{registration}/edit', [EventRegistrationController::class, 'edit'])->name('my_registrations.edit');
Route::put('/my-registrations/{registration}', [EventRegistrationController::class, 'update'])->name('my_registrations.update');


/* Admin Routes */
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/admin/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::delete('/admin/users/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('admin/events', [EventController::class, 'adminEvents'])->name('admin.events');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

});

Auth::routes();
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/home', [HomeController::class, 'index'])->name('/');