<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::ordered()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'status' => 'required|in:en_cours,termine,archive',
            'completed_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        // Générer le slug
        $validated['slug'] = Str::slug($validated['title']);

        // Transformer les technologies en JSON
        $technologies = array_map('trim', explode(',', $validated['technologies']));
        $validated['technologies'] = json_encode($technologies);

        // Supprimer les images du tableau validated (on les gère après)
        unset($validated['images']);

        // Créer le projet
        $project = Project::create($validated);

        // Gérer l'upload des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('projects', 'public');

                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $path,
                    'is_main' => $index === 0, // La première image est principale
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projet créé avec succès !');
    }

    public function edit(Project $project)
    {
        // Charger toutes les images, triées par ordre
        $project->load(['images' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);

        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'status' => 'required|in:en_cours,termine,archive',
            'completed_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'order' => 'integer|min:0',
            'main_image_id' => 'nullable|exists:project_images,id',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:project_images,id',
        ]);

        // Générer le slug
        $validated['slug'] = Str::slug($validated['title']);

        // Transformer les technologies en JSON
        $technologies = array_map('trim', explode(',', $validated['technologies']));
        $validated['technologies'] = json_encode($technologies);

        // Supprimer les champs non nécessaires
        unset($validated['images'], $validated['main_image_id'], $validated['delete_images']);

        // Mettre à jour le projet
        $project->update($validated);

        // Supprimer les images sélectionnées
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProjectImage::find($imageId);
                if ($image && $image->project_id === $project->id) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Définir l'image principale
        if ($request->main_image_id) {
            // Retirer is_main de toutes les images
            $project->images()->update(['is_main' => false]);
            // Définir la nouvelle image principale
            ProjectImage::where('id', $request->main_image_id)
                ->where('project_id', $project->id)
                ->update(['is_main' => true]);
        }

        // Gérer l'upload des nouvelles images
        if ($request->hasFile('images')) {
            $currentMaxOrder = $project->images()->max('order') ?? -1;

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('projects', 'public');

                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $path,
                    'is_main' => $project->images()->count() === 0 && $index === 0,
                    'order' => $currentMaxOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projet modifié avec succès !');
    }

    public function destroy(Project $project)
    {
        // Supprimer toutes les images associées
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projet supprimé avec succès !');
    }
}
