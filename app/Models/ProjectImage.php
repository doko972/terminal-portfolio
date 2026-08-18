<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    // URL publique de cette image
    public function getUrlAttribute(): string
    {
        return Storage::url($this->image_path);
    }
}
