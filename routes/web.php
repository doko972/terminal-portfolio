<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Détail d'un projet
Route::get('/projet/{slug}', [HomeController::class, 'show'])->name('project.show');

// Timeline / Parcours professionnel
Route::get('/parcours', [App\Http\Controllers\TimelineController::class, 'index'])->name('timeline');

// Contact
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

// API pour récupérer un projet (si nécessaire)
Route::get('/api/project/{id}', function($id) {
    $project = \App\Models\Project::findOrFail($id);
    return response()->json($project);
})->name('api.project');

/*
|--------------------------------------------------------------------------
| Routes Authentifiées
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Routes Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // CRUD Projets
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);
    
    // CRUD Expériences
    Route::resource('experiences', App\Http\Controllers\Admin\ExperienceController::class);
    
    // Réordonner les expériences (drag & drop futur)
    Route::post('experiences/reorder', [App\Http\Controllers\Admin\ExperienceController::class, 'reorder'])
        ->name('experiences.reorder');
});

/*
|--------------------------------------------------------------------------
| Routes d'authentification
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';