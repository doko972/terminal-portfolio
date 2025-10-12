<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        Project::create($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projet créé avec succès !');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projet modifié avec succès !');
    }

    public function destroy(Project $project)
    {
        // Supprimer l'image associée
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projet supprimé avec succès !');
    }
}