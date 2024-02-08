<?php

use App\Http\Controllers\AdviceController;
use App\Http\Controllers\AsocialBehaviorController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SocioPedagogicalCharacteristicController;
use App\Http\Controllers\ExpulsionController;
use App\Http\Controllers\LeadershipController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\GroupAchievementController;
use App\Http\Controllers\StudentCharacteristicController;
use App\Http\Controllers\StudentEmploymentController;
use App\Http\Controllers\StudentRelativeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GroupStudentController;
use App\Http\Controllers\GroupFamilyAttendanceController;
use App\Http\Controllers\InteractionWithParentController;
use App\Http\Controllers\StudentAchievementController;
use App\Models\InteractionWithParent;
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

        Route::get('/plans/{month}', [PlanController::class, 'index'])->name('plans.index');
        Route::post('/plans/{month}', [PlanController::class, 'sync'])->name('plans.sync');
        Route::get('/plans/{month}/print', [PlanController::class, 'print'])->name('plans.print');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{month}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{month}/load-plan', [ReportController::class, 'loadPlan'])->name('reports.load-plan');
        Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
        Route::post('/reports/{month}', [ReportController::class, 'sync'])->name('reports.sync');

        Route::get('/education-level', [EducationLevelController::class, 'index'])->name('education-level.index');
        Route::post('/education-level', [EducationLevelController::class, 'sync'])->name('education-level.sync');

        Route::get('/achievements/print', [GroupAchievementController::class, 'print'])->name('achievements.print');
    });

Route::controller(StudentCharacteristicController::class)
    ->prefix('courses/{course}/students/{student}/characteristics/{characteristic}')
    ->name('courses.students.characteristics.')
    ->group(function () {
        Route::post('/attach', 'attach')->name('attach');
        Route::delete('/detach', 'detach')->name('detach');
    });

Route::get('groups/{group}/students/{student_number}/relatives/print', [
    StudentRelativeController::class,
    'print',
])->name('groups.students.relatives.print');

Route::prefix('groups/{group}')
    ->name('groups.')
    ->group(function () {
        Route::get('/family-attendance', [GroupFamilyAttendanceController::class, 'index'])->name(
            'family-attendances.index'
        );

        Route::post('/family-attendance', [GroupFamilyAttendanceController::class, 'sync'])->name(
            'family-attendances.sync'
        );

        Route::get('/family-attendance/print', [GroupFamilyAttendanceController::class, 'print'])->name(
            'family-attendances.print'
        );

        Route::get('/interaction-with-parents/print', [InteractionWithParentController::class, 'print'])->name(
            'interaction-with-parents.print'
        );

        Route::get('/advice/print', [AdviceController::class, 'print'])->name('advice.print');
    });

Route::resource('groups.interaction-with-parents', InteractionWithParentController::class)->except(['show']);
Route::resource('groups.advice', AdviceController::class)->except(['show']);
Route::resource('groups.students', GroupStudentController::class);
Route::resource('groups.students.relatives', StudentRelativeController::class)->except(['show']);
Route::resource('groups.students.achievements', StudentAchievementController::class)->except(['index', 'show']);
Route::resource('groups.students.asocial-behaviors', AsocialBehaviorController::class)->except(['index', 'show']);
Route::resource('groups.courses.expulsions', ExpulsionController::class)->except(['show']);
Route::resource('groups.courses.achievements', GroupAchievementController::class)->except(['show']);
