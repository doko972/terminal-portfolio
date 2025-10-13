<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'image_path',
        'is_main',
        'order',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    // Relation avec Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Scope pour l'image principale
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    // Scope pour trier par ordre
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }

    // Image principale
    public function mainImage()
    {
        return $this->hasOne(ProjectImage::class)->where('is_main', true);
    }

    // Images triées
    public function orderedImages()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order', 'asc');
    }

    // Accesseur pour obtenir l'URL de l'image principale
    public function getMainImageUrlAttribute()
    {
        $mainImage = $this->mainImage;
        return $mainImage ? Storage::url($mainImage->image_path) : null;
    }
}
