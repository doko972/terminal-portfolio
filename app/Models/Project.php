<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    
    // Slug automatique si aucun n'est fourni
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($project) {
            if (empty($project->slug)) {
                $project->slug = static::uniqueSlug($project->title, $project->getKey());
            }
        });
    }

    /**
     * Construit un slug unique à partir d'un titre.
     *
     * La colonne `slug` porte une contrainte UNIQUE : sans suffixe, deux projets
     * au même titre provoquent une QueryException. On incrémente donc jusqu'à
     * trouver un slug libre, en ignorant le projet en cours d'édition.
     */
    public static function uniqueSlug(string $title, $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'projet';
        $slug = $base;
        $suffix = 2;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
    
    /**
     * Scope pour les projets visibles (basé sur le statut)
     */
    public function scopeVisible($query)
    {
        return $query->whereIn('status', ['termine', 'en_cours']);
        // Ou si vous voulez uniquement les projets terminés :
        // return $query->where('status', 'termine');
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