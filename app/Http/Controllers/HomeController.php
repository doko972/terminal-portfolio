<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer tous les projets terminés avec leurs images, triés par ordre et featured en premier
        $projects = Project::where('status', 'termine')
            ->with('images')
            ->ordered()
            ->get();

        // Récupérer les projets mis en avant
        $featuredProjects = $projects->where('is_featured', true);

        // Récupérer toutes les technologies uniques pour les filtres
        $allTechnologies = $projects->pluck('technologies_array')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('welcome', compact('projects', 'featuredProjects', 'allTechnologies'));
    }

    public function show($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        return view('project-detail', compact('project'));
    }
}
