<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CropWaterRecommendationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImpactReportController;
use App\Http\Controllers\IrrigationPlannerController;
use App\Http\Controllers\OperationsController;
use App\Http\Controllers\WaterPointController;
use App\Http\Controllers\WaterProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('auth.login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/irrigation-planner', [IrrigationPlannerController::class, 'create'])->name('irrigation-planner.create');
    Route::post('/irrigation-planner', [IrrigationPlannerController::class, 'store'])->name('irrigation-planner.store');
    Route::get('/crop-water-recommendations', [CropWaterRecommendationController::class, 'create'])->name('crop-water-recommendations.create');
    Route::post('/crop-water-recommendations', [CropWaterRecommendationController::class, 'store'])->name('crop-water-recommendations.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('water-projects', WaterProjectController::class);
    Route::get('/water-points', [WaterPointController::class, 'index'])->name('water-points.index');
    Route::get('/water-points/{waterSource}', [WaterPointController::class, 'show'])->name('water-points.show');
    Route::post('/water-points/{waterSource}/inspections', [WaterPointController::class, 'storeInspection'])->name('water-points.inspections.store');
    Route::post('/water-points/{waterSource}/maintenance', [WaterPointController::class, 'storeMaintenance'])->name('water-points.maintenance.store');
    Route::post('/water-points/{waterSource}/confirm', [WaterPointController::class, 'confirmFunctional'])->name('water-points.confirm');
    Route::post('/water-points/{waterSource}/incidents', [WaterPointController::class, 'storeIncident'])->name('water-points.incidents.store');
    Route::post('/water-points/{waterSource}/subscribe', [WaterPointController::class, 'subscribe'])->name('water-points.subscribe');
    Route::post('/water-points/{waterSource}/assignments', [WaterPointController::class, 'assignTechnician'])->name('water-points.assignments.store');
    Route::post('/water-points/{waterSource}/decommission', [WaterPointController::class, 'decommission'])->name('water-points.decommission');
    Route::post('/assignments/{assignment}/complete', [WaterPointController::class, 'completeAssignment'])->name('assignments.complete');
    Route::get('/impact-reports', [ImpactReportController::class, 'index'])->name('impact-reports.index');
    Route::get('/impact-reports/print', [ImpactReportController::class, 'print'])->name('impact-reports.print');
    Route::get('/impact-reports/export', [ImpactReportController::class, 'export'])->name('impact-reports.export');
    Route::get('/operations', OperationsController::class)->name('operations.index');
});
