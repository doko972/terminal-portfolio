<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Experience;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        // Récupérer les projets pour l'accueil
        $projects = Project::visible()
            ->ordered()
            ->take(6) // Limiter à 6 projets
            ->get();
        
        // Récupérer les projets mis en avant
        $featuredProjects = Project::visible()
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
            'experiences_count' => Experience::visible()->where('type', 'work')->count(),
            'years_experience' => $this->calculateYearsExperience(),
        ];
        
        return view('welcome', compact('projects', 'featuredProjects', 'allTechnologies', 'experiences', 'stats'));
    }
    
    /**
     * Calculer les années d'expérience
     */
    private function calculateYearsExperience()
    {
        $experiences = Experience::visible()
            ->where('type', 'work')
            ->get();
       
        $totalMonths = 0;
       
        foreach ($experiences as $exp) {
            $start = \Carbon\Carbon::parse($exp->start_date);
            $end = $exp->is_current ? now() : \Carbon\Carbon::parse($exp->end_date);
           
            if ($end) {
                $diff = $start->diff($end);
                $totalMonths += ($diff->y * 12) + $diff->m;
            }
        }
       
        return round($totalMonths / 12, 1);
    }
}