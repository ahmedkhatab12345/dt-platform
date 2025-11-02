<?php

use App\Http\Controllers\AdminRolePermissionController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GovernmentEntityController;
use App\Http\Controllers\PerformanceAnalysisController;
use App\Http\Controllers\PerspectiveController;
use App\Http\Controllers\PillarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
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
});

require __DIR__.'/auth.php';
