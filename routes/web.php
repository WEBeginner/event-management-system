<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// you may change the  routes here if you want to
// Public routes
Route::get('/', [EventController::class, 'index'])->name('home');
Route::resource('events', EventController::class)->only(['index', 'show']);

// Auth routes
Route::middleware(['auth'])->group(function () {
    // Events
    Route::resource('events', EventController::class)->except(['index', 'show']);
    
    // Attendees
    Route::post('/events/{event}/attend', [AttendeeController::class, 'store'])
         ->name('events.attend');
    Route::delete('/events/{event}/cancel', [AttendeeController::class, 'destroy'])
         ->name('events.cancel');
         
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Categories (Admin only)
    Route::resource('categories', CategoryController::class)->middleware('can:admin');
});

