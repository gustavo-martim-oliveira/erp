<?php

use App\Http\Controllers\Central\Pages\ACL\IndexController;
use App\Http\Controllers\Central\Pages\ACL\PermissionController;
use App\Http\Controllers\Central\Pages\ACL\RoleController;
use App\Http\Controllers\Central\Pages\Settings\SettingsController;
use App\Http\Controllers\Central\Pages\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\AuthController::class, 'signin'])->name('sign');
Route::post('/signin', [App\Http\Controllers\AuthController::class, 'doSignin'])->name('signin');
Route::get('/signout', [App\Http\Controllers\AuthController::class, 'signout'])->name('singout');


Route::group([
    'prefix' => 'central',
    'as' => 'central.',
    'middleware' => ['auth'],

], function () {


    Route::get('dashboard', function () {
        return view('central.layout.app', ['title' => 'Dashboard', 'page' => 'dashboard']);
    })->name('dashboard')->middleware('auth');

    Route::prefix('acl')
        ->name('acl.')
        ->middleware('can:acl')
        ->group(function () {

            Route::get('/', IndexController::class)
                ->name('index');

            Route::resource('roles', RoleController::class)
                ->except(['index', 'show', 'create', 'edit']);

            Route::resource('permissions', PermissionController::class)
                ->except(['index', 'show', 'create', 'edit']);
                
        });

    Route::prefix('users')
        ->name('users.')
        ->controller(UserController::class)
        ->middleware('can:users')
        ->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::post('/', 'store')
                ->name('store');

            Route::get('/{user}/edit', 'edit')
                ->name('edit');

            Route::put('/{user}', 'update')
                ->name('update');

            Route::delete('/{user}', 'destroy')
                ->name('destroy');

        });

        Route::prefix('settings')
        ->name('settings.')
        ->middleware('can:config_system')
        ->group(function () {

        
            Route::get('/', [SettingsController::class, 'index'])
                ->name('index');

            Route::post('/', [SettingsController::class, 'update'])
                ->name('update');

            Route::post('/export-db', [SettingsController::class, 'exportDatabase'])
                ->name('export-db');

        });

});