<?php

use App\Http\Controllers\Admin\ExperienceController as AdminExperienceController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\CvController as AdminCvController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimelineController;
use App\Models\Project;
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
Route::get('/parcours', [TimelineController::class, 'index'])->name('timeline');

// Téléchargement du CV (le fichier vit sur le disque privé : cette route est
// son unique point d'accès public)
Route::get('/cv', [CvController::class, 'download'])->name('cv.download');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])
    ->middleware('throttle:5,1')
    ->name('contact.send');

// API pour récupérer un projet (si nécessaire).
// Limitée aux projets publiés : un projet archivé ne doit pas fuiter ici.
Route::get('/api/project/{id}', function ($id) {
    $project = Project::visible()->with('images')->findOrFail($id);

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

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Réordonner les expériences (drag & drop futur).
    // Déclarée avant la resource pour ne pas être capturée par /experiences/{experience}.
    Route::post('experiences/reorder', [AdminExperienceController::class, 'reorder'])
        ->name('experiences.reorder');

    // CRUD Projets
    Route::resource('projects', AdminProjectController::class)->except(['show']);

    // CRUD Expériences
    Route::resource('experiences', AdminExperienceController::class)->except(['show']);

    // CV téléchargeable
    Route::get('cv', [AdminCvController::class, 'edit'])->name('cv.edit');
    Route::post('cv', [AdminCvController::class, 'update'])->name('cv.update');
    Route::delete('cv', [AdminCvController::class, 'destroy'])->name('cv.destroy');
});

/*
|--------------------------------------------------------------------------
| Routes d'authentification
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
