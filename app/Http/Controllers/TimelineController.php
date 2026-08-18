<?php

namespace App\Http\Controllers;

use App\Models\Experience;

class TimelineController extends Controller
{
    /**
     * Afficher la timeline professionnelle
     */
    public function index()
    {
        // Récupérer toutes les expériences visibles, triées par ordre et date
        $experiences = Experience::visible()
            ->ordered()
            ->get();

        // Grouper par type si besoin
        $workExperiences = $experiences->where('type', 'work');
        $educations = $experiences->where('type', 'education');
        $certifications = $experiences->where('type', 'certification');

        // Statistiques
        $stats = [
            'total_years' => Experience::totalYears($workExperiences),
            'companies_count' => $workExperiences->unique('company')->count(),
            'certifications_count' => $certifications->count(),
        ];

        return view('timeline', compact(
            'experiences',
            'workExperiences',
            'educations',
            'certifications',
            'stats'
        ));
    }
}