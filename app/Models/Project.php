<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'technologies',
        'image',
        'url',
        'github_url',
        'status',
        'completed_at',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'completed_at' => 'date',
        'is_featured' => 'boolean',
    ];

    // Accesseur pour transformer le JSON des technologies
    public function getTechnologiesArrayAttribute()
    {
        return json_decode($this->technologies, true) ?? [];
    }

    // Mutateur pour le slug automatique
    public static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });

        static::updating(function ($project) {
            if ($project->isDirty('title') && empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    // Scope pour les projets mis en avant
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope pour les projets terminés
    public function scopeCompleted($query)
    {
        return $query->where('status', 'termine');
    }

    // Scope pour trier par ordre
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('completed_at', 'desc');
    }

    // RELATION AVEC LES IMAGES
    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order', 'asc');
    }

    // Obtenir l'image principale
    public function getMainImageAttribute()
    {
        return $this->images()->where('is_main', true)->first();
    }
}