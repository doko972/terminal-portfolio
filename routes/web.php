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

// Routes Contact
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

// Routes Admin - Expériences professionnelles
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard et autres routes admin existantes...
    
    // CRUD Expériences
    Route::resource('experiences', App\Http\Controllers\Admin\ExperienceController::class);
    
    // Route pour réordonner les expériences (drag & drop futur)
    Route::post('experiences/reorder', [App\Http\Controllers\Admin\ExperienceController::class, 'reorder'])
        ->name('experiences.reorder');
});

// Routes publiques
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route Timeline / Parcours
Route::get('/parcours', [App\Http\Controllers\TimelineController::class, 'index'])->name('timeline');

// Ou si vous voulez l'intégrer dans une page "À propos"
// Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])->name('about');

require __DIR__.'/auth.php';