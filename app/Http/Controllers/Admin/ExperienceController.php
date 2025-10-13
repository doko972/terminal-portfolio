<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $experiences = Experience::ordered()->paginate(15);
        
        return view('admin.experiences.index', compact('experiences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.experiences.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:work,education,certification',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'description' => 'nullable|string',
            'tasks' => 'nullable|string',
            'technologies' => 'nullable|string',
            'company_url' => 'nullable|url',
            'certificate_url' => 'nullable|url',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_visible' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        // Si "is_current" est coché, on met end_date à null
        if ($request->has('is_current') && $request->is_current) {
            $validated['end_date'] = null;
        }

        // Traiter les tâches (une par ligne -> array JSON)
        if ($request->filled('tasks')) {
            $validated['tasks'] = array_filter(
                array_map('trim', explode("\n", $request->tasks))
            );
        }

        // Traiter les technologies (séparées par virgules -> array JSON)
        if ($request->filled('technologies')) {
            $validated['technologies'] = array_filter(
                array_map('trim', explode(',', $request->technologies))
            );
        }

        // Upload du logo
        if ($request->hasFile('company_logo')) {
            $validated['company_logo'] = $request->file('company_logo')
                ->store('experiences/logos', 'public');
        }

        // Ordre par défaut
        if (!isset($validated['order'])) {
            $validated['order'] = Experience::max('order') + 1;
        }

        Experience::create($validated);

        return redirect()->route('admin.experiences.index')
            ->with('success', '✅ Expérience ajoutée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Experience $experience)
    {
        return view('admin.experiences.show', compact('experience'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Experience $experience)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:work,education,certification',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'description' => 'nullable|string',
            'tasks' => 'nullable|string',
            'technologies' => 'nullable|string',
            'company_url' => 'nullable|url',
            'certificate_url' => 'nullable|url',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_visible' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        // Si "is_current" est coché, on met end_date à null
        if ($request->has('is_current') && $request->is_current) {
            $validated['end_date'] = null;
        }

        // Traiter les tâches
        if ($request->filled('tasks')) {
            $validated['tasks'] = array_filter(
                array_map('trim', explode("\n", $request->tasks))
            );
        } else {
            $validated['tasks'] = [];
        }

        // Traiter les technologies
        if ($request->filled('technologies')) {
            $validated['technologies'] = array_filter(
                array_map('trim', explode(',', $request->technologies))
            );
        } else {
            $validated['technologies'] = [];
        }

        // Upload du logo (et suppression de l'ancien si nouveau)
        if ($request->hasFile('company_logo')) {
            // Supprimer l'ancien logo
            if ($experience->company_logo) {
                Storage::disk('public')->delete($experience->company_logo);
            }
            
            $validated['company_logo'] = $request->file('company_logo')
                ->store('experiences/logos', 'public');
        }

        $experience->update($validated);

        return redirect()->route('admin.experiences.index')
            ->with('success', '✅ Expérience modifiée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Experience $experience)
    {
        // Supprimer le logo si existe
        if ($experience->company_logo) {
            Storage::disk('public')->delete($experience->company_logo);
        }

        $experience->delete();

        return redirect()->route('admin.experiences.index')
            ->with('success', '✅ Expérience supprimée avec succès !');
    }

    /**
     * Réordonner les expériences (pour drag & drop futur)
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:experiences,id',
        ]);

        foreach ($request->order as $index => $id) {
            Experience::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}