<?php

namespace App\Models;

use Carbon\Carbon;
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
     * Date de fin effective : aujourd'hui si le poste est en cours, sinon la
     * date de fin renseignée. Renvoie null si l'expérience n'est ni en cours
     * ni terminée — auquel cas sa durée est inconnue et ne doit pas être
     * comptabilisée.
     */
    public function getEffectiveEndDateAttribute(): ?Carbon
    {
        if ($this->is_current) {
            return Carbon::now();
        }

        return $this->end_date ? Carbon::parse($this->end_date) : null;
    }

    /**
     * Total d'années cumulées sur une collection d'expériences, arrondi à
     * une décimale. Les expériences sans date de fin exploitable sont ignorées.
     */
    public static function totalYears($experiences): float
    {
        $totalMonths = 0;

        foreach ($experiences as $experience) {
            $end = $experience->effective_end_date;

            if (! $end) {
                continue;
            }

            $diff = Carbon::parse($experience->start_date)->diff($end);
            $totalMonths += ($diff->y * 12) + $diff->m;
        }

        return round($totalMonths / 12, 1);
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
        $end = $this->effective_end_date;

        if (!$end) {
            return 'Durée non spécifiée';
        }

        $diff = Carbon::parse($this->start_date)->diff($end);
        
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
        $start = \Carbon\Carbon::parse($this->start_date)->format('M Y');
        
        if ($this->is_current) {
            return $start . ' - Aujourd\'hui';
        }
        
        if ($this->end_date) {
            $end = \Carbon\Carbon::parse($this->end_date)->format('M Y');
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
        
        $startMonth = $months[\Carbon\Carbon::parse($this->start_date)->month];
        $startYear = \Carbon\Carbon::parse($this->start_date)->year;
        $start = "$startMonth $startYear";
        
        if ($this->is_current) {
            return $start . ' - Aujourd\'hui';
        }
        
        if ($this->end_date) {
            $endMonth = $months[\Carbon\Carbon::parse($this->end_date)->month];
            $endYear = \Carbon\Carbon::parse($this->end_date)->year;
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