<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

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
            'total_years' => $this->calculateTotalYears($workExperiences),
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

    /**
     * Calculer le nombre total d'années d'expérience
     */
    private function calculateTotalYears($experiences)
    {
        $totalMonths = 0;

        foreach ($experiences as $experience) {
            $start = $experience->start_date;
            $end = $experience->is_current ? now() : $experience->end_date;

            if ($end) {
                $diff = $start->diff($end);
                $totalMonths += ($diff->y * 12) + $diff->m;
            }
        }

        return round($totalMonths / 12, 1);
    }
}