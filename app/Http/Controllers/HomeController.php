<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Project;
use App\Support\Cv;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        // Les images sont chargées ici pour deux raisons : éviter le N+1 dans
        // la boucle des cartes, et surtout parce que la modale JS lit
        // `project.images` depuis le @json() de la vue — sans eager loading la
        // relation n'est pas sérialisée et la galerie reste vide.
        $projects = Project::visible()
            ->with('images')
            ->ordered()
            ->take(6) // Limiter à 6 projets
            ->get();

        // Récupérer les projets mis en avant
        $featuredProjects = Project::visible()
            ->with('images')
            ->featured()
            ->ordered()
            ->take(3)
            ->get();

        // Récupérer toutes les technologies utilisées dans les projets
        $allTechnologies = Project::visible()
            ->get()
            ->pluck('technologies_array')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        // Récupérer les dernières expériences professionnelles
        $experiences = Experience::visible()
            ->byType('work') // Seulement les expériences pro
            ->ordered()
            ->take(3) // Limiter à 3 dernières expériences
            ->get();

        // Statistiques pour l'accueil (optionnel)
        $stats = [
            'projects_count' => Project::visible()->count(),
            'experiences_count' => Experience::visible()->byType('work')->count(),
            'years_experience' => Experience::totalYears(
                Experience::visible()->byType('work')->get()
            ),
        ];

        // Le bouton de téléchargement du hero reste masqué tant qu'aucun CV
        // n'a été mis en ligne depuis l'administration.
        $hasCv = Cv::exists();

        return view('welcome', compact('projects', 'featuredProjects', 'allTechnologies', 'experiences', 'stats', 'hasCv'));
    }

    /**
     * Afficher le détail d'un projet (lien permanent partageable).
     */
    public function show(string $slug)
    {
        $project = Project::visible()
            ->with('images')
            ->where('slug', $slug)
            ->firstOrFail();

        $otherProjects = Project::visible()
            ->with('images')
            ->whereKeyNot($project->id)
            ->ordered()
            ->take(3)
            ->get();

        return view('projects.show', compact('project', 'otherProjects'));
    }
}
