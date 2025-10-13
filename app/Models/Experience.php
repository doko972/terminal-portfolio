<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company',
        'location',
        'company_logo',
        'type',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'tasks',
        'technologies',
        'company_url',
        'certificate_url',
        'is_visible',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'is_visible' => 'boolean',
        'tasks' => 'array',
        'technologies' => 'array',
    ];

    /**
     * Scope pour récupérer uniquement les expériences visibles
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope pour trier par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')
                    ->orderBy('start_date', 'desc');
    }

    /**
     * Scope par type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Récupérer l'URL du logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->company_logo) {
            return Storage::url($this->company_logo);
        }
        return null;
    }

    /**
     * Calculer la durée de l'expérience
     */
    public function getDurationAttribute()
    {
        $end = $this->is_current ? now() : $this->end_date;
        
        if (!$end) {
            return 'Durée non spécifiée';
        }

        $diff = $this->start_date->diff($end);
        
        $years = $diff->y;
        $months = $diff->m;
        
        $duration = [];
        
        if ($years > 0) {
            $duration[] = $years . ' an' . ($years > 1 ? 's' : '');
        }
        
        if ($months > 0) {
            $duration[] = $months . ' mois';
        }
        
        return !empty($duration) ? implode(' et ', $duration) : 'Moins d\'un mois';
    }

    /**
     * Formater la période
     */
    public function getPeriodAttribute()
    {
        // Format court : Jan 2023
        $start = $this->start_date->format('M Y');
        
        if ($this->is_current) {
            return $start . ' - Aujourd\'hui';
        }
        
        if ($this->end_date) {
            $end = $this->end_date->format('M Y');
            return $start . ' - ' . $end;
        }
        
        return $start;
    }
    
    /**
     * Formater la période en français
     */
    public function getPeriodFrAttribute()
    {
        $months = [
            1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
        ];
        
        $startMonth = $months[$this->start_date->month];
        $startYear = $this->start_date->year;
        $start = "$startMonth $startYear";
        
        if ($this->is_current) {
            return $start . ' - Aujourd\'hui';
        }
        
        if ($this->end_date) {
            $endMonth = $months[$this->end_date->month];
            $endYear = $this->end_date->year;
            $end = "$endMonth $endYear";
            return $start . ' - ' . $end;
        }
        
        return $start;
    }

    /**
     * Récupérer les tâches sous forme de tableau
     */
    public function getTasksArrayAttribute()
    {
        return is_array($this->tasks) ? $this->tasks : [];
    }

    /**
     * Récupérer les technologies sous forme de tableau
     */
    public function getTechnologiesArrayAttribute()
    {
        return is_array($this->technologies) ? $this->technologies : [];
    }
}