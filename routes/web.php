<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\MainPageController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthenticatedUserController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


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

// Home
Route::redirect('/', '/login');

Route::controller(UserController::class)->group(function () {
    Route::get('/user/findAll', 'findAll');
});

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});


// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(EventController::class)->group(function () {
    Route::post('/create', 'createEvent')->name('createEvent');
    Route::post('/edit', 'editEvent')->name('editEvent');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Admin Dashboard
Route::controller(AdminController::class)->group(function () {
    Route::get('/dashboard', 'showDashboard')->name('dashboard');
});

Route::controller(AuthenticatedUserController::class)->group(function () {
    Route::get('/user/{id}/events', 'showUserEvents')->name('user.events');
});

Route::controller(EventController::class)->group(function () {
    Route::delete('/events/{id}', 'deleteEvent')->name('events.delete');
    Route::get('/events', 'listPublicEvents')->name('events');
    Route::get('/events/{id}/details', 'showEventDetails')->name('events.details');
});

