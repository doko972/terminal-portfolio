<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use function Ramsey\Uuid\v1;

// Page d'accueil publique
Route::get('/', [HomeController::class, 'index'])->name('home');

// Détail d'un projet
Route::get('/projet/{slug}', [HomeController::class, 'show'])->name('project.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes Admin - Projets
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);
});

// API pour récupérer un projet
Route::get('/api/project/{id}', function($id) {
    $project = \App\Models\Project::findOrFail($id);
    return response()->json($project);
})->name('api.project');

require __DIR__.'/auth.php';