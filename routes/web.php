<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ImportJsonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\EventDataController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationAuthController;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/import', [ImportJsonController::class, 'start'])->name('import');

Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->where('provider', 'google|apple')->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback']);

Route::post('/logout', function () {

    if (Auth::guard('organization')->check()) {
        Auth::guard('organization')->logout();
    }

    if (Auth::check()) {
        Auth::logout();
    }

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('index');

})->name('logout');


Route::any('/', [EventController::class, 'index'])->name('index');

Route::get('/faq', [EventController::class, 'faq'])->name('faq');

Route::get('/about', [EventController::class, 'about'])->name('about');

Route::get('/event-detail/{slug}', [EventController::class, 'eventDetail'])->name('event-detail');

Route::get('/event-list', [EventController::class, 'eventList'])->name('event-list');

Route::get('/privacy-policy', [EventController::class, 'privacyPolicy'])->name('privacy-policy');

Route::get('/term-condition', [EventController::class, 'termCondition'])->name('term-condition');

Route::get('/disclaimer', [EventController::class, 'disclaimer'])->name('disclaimer');

Route::get('/messages', [MessageController::class, 'fetchMessages']);
Route::post('/messages', [MessageController::class, 'sendMessage']);

Route::resource('contact', ContactController::class);

Route::get('/map', [EventController::class, 'map'])->name('map');

Route::get('/map-data', [EventController::class, 'mapData'])->name('map.data');

// organization auth
Route::post('/organization/register', [OrganizationAuthController::class, 'register'])
    ->name('organization.register');

Route::post('/organization/login', [OrganizationAuthController::class, 'login'])
    ->name('organization.login');

Route::middleware('auth:organization')->prefix('organization')->group(function () {
    Route::resource('events', EventDataController::class)->except(['show']);
});

//     Route::get('/login', function () {
//         return redirect('/');
// })->name('login');