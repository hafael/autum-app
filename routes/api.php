<?php

use App\Http\Controllers\API\ApplicationsController;
use App\Http\Controllers\API\TeamsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/applications', [ApplicationsController::class, 'index'])
        ->middleware(['auth:sanctum'])
        ->name('api.applications.index');

Route::group([
    'middleware' => ['auth:sanctum', 'verified'],
    'prefix' => 'teams',
    'as' => 'api.teams.'
], function(){
    Route::get('/', [TeamsController::class, 'index'])->name('index');
    Route::get('/{team}', [TeamsController::class, 'show'])->name('show');
});