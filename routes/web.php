<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyResponseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/map', [MapController::class, 'index'])->name('map.index');
    Route::get('/map/imported-records', [MapController::class, 'importedRecords'])->name('map.imported-records');
    Route::get('/map/survey-responses', [MapController::class, 'surveyResponses'])->name('map.survey-responses');
    Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');
    Route::post('/imports/upload', [ImportController::class, 'upload'])->name('imports.upload');
    Route::post('/imports', [ImportController::class, 'store'])->name('imports.store');
    Route::resource('areas', AreaController::class);
    Route::resource('surveys', SurveyController::class);
    Route::get('/surveys/{survey}/fill', [SurveyResponseController::class, 'create'])->name('surveys.fill');
    Route::post('/surveys/{survey}/responses', [SurveyResponseController::class, 'store'])->name('surveys.responses.store');
});

require __DIR__.'/auth.php';
