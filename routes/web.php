<?php

use App\Http\Controllers\MainPageController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthenticatedUserController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\FileController;


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

Route::redirect('/', '/login');

Route::controller(UserController::class)->group(function () {
    Route::get('/user/findAll', 'findAll');
    Route::get('/user/{id}/notifications', 'showUserNotifications')->name('user.notifications');
});

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::controller(AdminController::class)->group(function () {
    Route::get('/dashboard', 'showDashboard')->name('dashboard');
    Route::get('/report/{id}/details', 'showReportDetails')->name('report.details');
    Route::post('/dashboard/tag/add', 'addTag')->name('tag.add');
    Route::delete('/dashboard/tag/{id}/delete', 'deleteTag')->name('tag.delete');
    Route::get('/dashboard/users', 'showUsers')->name('admin.users');
    Route::get('/dashboard/events', 'showEvents')->name('admin.events');
    Route::get('/dashboard/reports', 'showReports')->name('admin.reports');
    Route::get('/dashboard/tags', 'showTags')->name('admin.tags');
    Route::get('/search-users', 'searchUsers')->name('admin-search-users');
    Route::get('/search-events', 'searchEvents')->name('admin-search-events');
});

Route::controller(AuthenticatedUserController::class)->group(function () {
    Route::get('/user/{id}/events', 'showUserEvents')->name('user.events');
    Route::get('/user/{id}/events/created', 'showUserjoinedEvents')->name('user.events.joined');
    Route::get('/user/{id}/events/joined', 'showUserattendedEvents')->name('user.events.attended');
    Route::get('/user/{id}/events/favorites', 'showUserFavouriteEvents')->name('user.events.favorites');
    Route::get('/user/{id}/profile', 'showUserProfile')->name('user.profile');
    Route::post('/user/{id}/profile/edit', 'updateUserProfile')->name('user.profile.update');
    Route::post('/user/{id}/password/change', 'updateUserPassword')->name('user.password.update');
    Route::post('/user/{id}/delete', 'deleteUser')->name('user.delete');
    Route::post('/user/{id}/verify', 'verifyUser')->name('user.verify');
});

Route::controller(EventController::class)->group(function () {
    Route::post('/events/create', 'createEvent')->name('events.create');
    Route::post('/events/edit/{id}', 'editEvent')->name('events.edit');
    Route::delete('/events/delete/{id}', 'deleteEvent')->name('events.delete');
    Route::post('/events/cancel/{id}', 'cancelEvent')->name('events.cancel');
    Route::get('/events', 'listEvents')->name('events');
    Route::get('/events/{id}/details', 'showEventDetails')->name('events.details');
    Route::post('/events/{id}/leave', 'leaveEvent')->name('event.leave');
    Route::post('/events/{id}/join', 'joinEvent')->name('event.join');
    Route::post('/events/{id}/add', 'addUser')->name('events.add');
    Route::post('/events/{id}/addFavourite', 'addEventAsFavourite')->name('event.favourite');
    Route::post('/events/{id}/remove', 'removeUser')->name('events.remove');
    Route::post('/events/search', 'search')->name('search-events');
    Route::post('/events/{id}/invite', 'addNotification')->name('events.notification');
    Route::delete('/notifications/{notificationId}/delete', 'deleteNotification')->name('notification.delete');
    Route::post('/events/order', 'order')->name('events.order');
    Route::post('/events/filter', 'filter')->name('events.filter');
    Route::post('/events/{id}/report', 'reportEvent')->name('events.report');
    Route::post('/events/{id}/addFavourite', 'addEventAsFavourite')->name('event.favourite');
    Route::post('/events/{id}/removeFavourite', 'removeEventAsFavourite')->name('event.removeFavourite');
});

Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirect')->name('google-auth');
    Route::get('auth/google/call-back', 'callbackGoogle')->name('google-call-back');
});

Route::controller(FileController::class)->group(function () {
    Route::post('/file/upload', 'upload')->name('file.upload');
});

Route::get('/about', 'App\Http\Controllers\AboutController@index')->name('about');
