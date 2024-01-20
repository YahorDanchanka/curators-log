<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SocioPedagogicalCharacteristicController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [MainController::class, 'index'])->name('home');

/** <Groups> */
Route::resource('groups', GroupController::class)->except(['show']);
Route::controller(SocioPedagogicalCharacteristicController::class)
    ->prefix('groups/{group}/courses/{course}/socio-pedagogical-characteristic')
    ->name('groups.courses.socio-pedagogical-characteristic.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'sync')->name('sync');
    });
/** </Groups> */
