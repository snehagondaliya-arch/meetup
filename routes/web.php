<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ImportJsonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/import',[ImportJsonController::class,'start'])->name('import');

Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->where('provider', 'google|apple')->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback']);

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('index');
})->name('logout');


Route::get('/', [EventController::class, 'index'])->name('index');

Route::get('/faq', [EventController::class, 'faq'])->name('faq');

Route::get('/about', [EventController::class, 'about'])->name('about');

Route::get('/contact', [EventController::class, 'contact'])->name('contact');

Route::get('/event-detail/{id}', [EventController::class, 'eventDetail'])->name('event-detail');

Route::get('/event-list', [EventController::class, 'eventList'])->name('event-list');

Route::get('/privacy-policy', [EventController::class, 'privacyPolicy'])->name('privacy-policy');

Route::get('/term-condition', [EventController::class, 'termCondition'])->name('term-condition');

Route::get('/disclaimer', [EventController::class, 'disclaimer'])->name('disclaimer');


Route::get('/messages', [MessageController::class, 'fetchMessages']);
Route::post('/messages', [MessageController::class, 'sendMessage']);