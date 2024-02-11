<?php

use App\Http\Controllers\AdviceController;
use App\Http\Controllers\AsocialBehaviorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CuratorController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SocioPedagogicalCharacteristicController;
use App\Http\Controllers\ExpulsionController;
use App\Http\Controllers\LeadershipController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\ExpertAdviceController;
use App\Http\Controllers\GroupAchievementController;
use App\Http\Controllers\StudentCharacteristicController;
use App\Http\Controllers\StudentEmploymentController;
use App\Http\Controllers\StudentRelativeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GroupStudentController;
use App\Http\Controllers\GroupFamilyAttendanceController;
use App\Http\Controllers\IndividualWorkController;
use App\Http\Controllers\InteractionWithParentController;
use App\Http\Controllers\StudentAchievementController;
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

Route::prefix('login')
    ->controller(AuthController::class)
    ->name('auth.')

    ->group(function () {
        Route::get('/', 'login')
            ->name('login')
            ->middleware('guest');

        Route::post('/', 'loginPost')
            ->name('login-post')
            ->middleware('guest');

        Route::post('/logout', 'logout')
            ->name('logout')
            ->middleware('auth');
    });

Route::resource('curators', CuratorController::class)
    ->except(['show'])
    ->middleware('auth');

Route::resource('groups', GroupController::class)
    ->except(['show'])
    ->middleware('auth');

Route::resource('groups.students', GroupStudentController::class)->middleware('auth');
Route::resource('groups.advice', AdviceController::class)
    ->except(['show'])
    ->middleware('auth');
Route::resource('groups.interaction-with-parents', InteractionWithParentController::class)
    ->except(['show'])
    ->middleware('auth');

Route::resource('groups.courses.achievements', GroupAchievementController::class)
    ->except(['show'])
    ->middleware('auth');
Route::resource('groups.courses.expulsions', ExpulsionController::class)
    ->except(['show'])
    ->middleware('auth');

Route::resource('groups.students.achievements', StudentAchievementController::class)
    ->except(['index', 'show'])
    ->middleware('auth');
Route::resource('groups.students.relatives', StudentRelativeController::class)
    ->except(['show'])
    ->middleware('auth');
Route::resource('groups.students.asocial-behaviors', AsocialBehaviorController::class)
    ->except(['index', 'show'])
    ->middleware('auth');
Route::resource('groups.students.expert-advice', ExpertAdviceController::class)
    ->except(['index', 'show'])
    ->middleware('auth');
Route::resource('groups.students.individual-works', IndividualWorkController::class)
    ->except(['index', 'show'])
    ->middleware('auth');

Route::controller(StudentCharacteristicController::class)
    ->prefix('courses/{course}/students/{student}/characteristics/{characteristic}')
    ->name('courses.students.characteristics.')
    ->middleware('auth')
    ->group(function () {
        Route::post('/attach', 'attach')->name('attach');
        Route::delete('/detach', 'detach')->name('detach');
    });

Route::prefix('groups/{group}')
    ->name('groups.')
    ->middleware('auth')
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

        Route::prefix('students/{student_number}')
            ->name('students.')
            ->group(function () {
                Route::get('/print', [GroupStudentController::class, 'print'])->name('print');
                Route::get('/relatives/print', [StudentRelativeController::class, 'print'])->name('relatives.print');
            });

        Route::prefix('/courses/{course_number}')
            ->name('courses.')
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
                Route::get('/reports/{month}/load-plan', [ReportController::class, 'loadPlan'])->name(
                    'reports.load-plan'
                );
                Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
                Route::post('/reports/{month}', [ReportController::class, 'sync'])->name('reports.sync');

                Route::get('/education-level', [EducationLevelController::class, 'index'])->name(
                    'education-level.index'
                );
                Route::get('/education-level/print', [EducationLevelController::class, 'print'])->name(
                    'education-level.print'
                );
                Route::post('/education-level', [EducationLevelController::class, 'sync'])->name(
                    'education-level.sync'
                );

                Route::get('/achievements/print', [GroupAchievementController::class, 'print'])->name(
                    'achievements.print'
                );

                Route::get('/socio-pedagogical-characteristic', [
                    SocioPedagogicalCharacteristicController::class,
                    'index',
                ])->name('socio-pedagogical-characteristic.index');
                Route::post('/socio-pedagogical-characteristic', [
                    SocioPedagogicalCharacteristicController::class,
                    'sync',
                ])->name('socio-pedagogical-characteristic.sync');
                Route::get('/socio-pedagogical-characteristic/print', [
                    SocioPedagogicalCharacteristicController::class,
                    'print',
                ])->name('socio-pedagogical-characteristic.print');
            });
    });
