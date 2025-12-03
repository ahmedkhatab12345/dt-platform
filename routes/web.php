<?php

use App\Http\Controllers\AdminRolePermissionController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GovernmentEntityController;
use App\Http\Controllers\Innovation\PlatformsController;
use App\Http\Controllers\Innovation\PartnershipController;
use App\Http\Controllers\Innovation\EventController;
use App\Http\Controllers\Innovation\FrameworkController;
use App\Http\Controllers\Innovation\InnovationAssessmentController;
use App\Http\Controllers\PerformanceAnalysisController;
use App\Http\Controllers\PerspectiveController;
use App\Http\Controllers\PillarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\GovernmentEntity;
use App\Models\Pillar;
use App\Models\Standard;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $totalUsers = User::count();
    $totalEntities = GovernmentEntity::count();
    $totalPillars = Pillar::count();
    $totalStandards = Standard::count();

    return view('dashboard', compact('totalUsers', 'totalEntities', 'totalPillars', 'totalStandards'));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('roles', AdminRolePermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('government_entities', GovernmentEntityController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('projects', ProjectController::class);
    Route::resources([
        'perspectives' => PerspectiveController::class,
        'pillars'      => PillarController::class,
        'standards'    => StandardController::class,
    ]);
    Route::resource('tools', ToolController::class);
    Route::resource('assignments', AssignmentController::class);
    Route::get('/performance_analysis/export', [PerformanceAnalysisController::class, 'export'])
    ->name('performance_analysis.export');

    Route::resource('performance_analysis', PerformanceAnalysisController::class)->except(['show']);

    Route::get('/reports/projects-planned', [ReportsController::class, 'plannedProjects'])
    ->name('reports.projects_planned');

    Route::get('/reports/projects_planned/export', [ReportsController::class, 'plannedProjectsExport'])
    ->name('reports.projects_planned.export');

    // Innovation
    Route::prefix('innovation')->name('innovation.')->group(function () {

        Route::resource('platforms', PlatformsController::class)->except(['show']);
        Route::resource('partnerships', PartnershipController::class)->except(['show']);
        Route::resource('events', EventController::class)->except(['show']);
        Route::resource('frameworks', FrameworkController::class)->except(['show']);
        Route::resource('assessment', InnovationAssessmentController::class);
    });        
});

require __DIR__.'/auth.php';
