<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    Route::get('login', '\Autum\SAML\Http\Controllers\Auth\SamlController@login')->middleware(['guest:'.config('fortify.guard')])->name('login');
    Route::get('logout', '\Autum\SAML\Http\Controllers\Auth\SamlController@logout')->name('logout');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::webhooks('webhook');