<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SocioPedagogicalCharacteristicController;
use App\Http\Controllers\ExpulsionController;
use App\Http\Controllers\LeadershipController;
use App\Http\Controllers\StudentCharacteristicController;
use App\Http\Controllers\StudentEmploymentController;
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
    ->prefix('groups/{group}/courses/{course_number}/socio-pedagogical-characteristic')
    ->name('groups.courses.socio-pedagogical-characteristic.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'sync')->name('sync');
    });
/** </Groups> */

Route::prefix('groups/{group}/courses/{course_number}')
    ->name('groups.courses.')
    ->group(function () {
        Route::get('/leadership', [LeadershipController::class, 'index'])->name('leadership.index');
        Route::post('/leadership', [LeadershipController::class, 'sync'])->name('leadership.sync');

        Route::get('/student-employment', [StudentEmploymentController::class, 'index'])->name(
            'student-employment.index'
        );

        Route::get('/student-employment/print', [StudentEmploymentController::class, 'print'])->name(
            'student-employment.print'
        );

        Route::post('/student-employment', [StudentEmploymentController::class, 'sync'])->name(
            'student-employment.sync'
        );
    });

Route::controller(StudentCharacteristicController::class)
    ->prefix('courses/{course}/students/{student}/characteristics/{characteristic}')
    ->name('courses.students.characteristics.')
    ->group(function () {
        Route::post('/attach', 'attach')->name('attach');
        Route::delete('/detach', 'detach')->name('detach');
    });

Route::resource('groups.courses.expulsions', ExpulsionController::class)->except(['show']);
