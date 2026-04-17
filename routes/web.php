<?php

use App\Http\Controllers\ImportJsonController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\StaticPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/import',[ImportJsonController::class,'start']);

Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->where('provider', 'google|apple');
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback']);

Route::view('/login', 'web.login');


Route::get('/events/{category?}', [StaticPageController::class, 'index'])->name('index');

Route::get('/faq', [StaticPageController::class, 'faq'])->name('faq');

Route::get('/about', [StaticPageController::class, 'about'])->name('about');

Route::get('/contact', [StaticPageController::class, 'contact'])->name('contact');

Route::get('/event-detail/{id}', [StaticPageController::class, 'eventDetail'])->name('event-detail');

Route::get('/event-list', [StaticPageController::class, 'eventList'])->name('event-list');

Route::get('/privacy-policy', [StaticPageController::class, 'privacyPolicy'])->name('privacy-policy');

Route::get('/term-condition', [StaticPageController::class, 'termCondition'])->name('term-condition');

Route::get('/disclaimer', [StaticPageController::class, 'disclaimer'])->name('disclaimer');
