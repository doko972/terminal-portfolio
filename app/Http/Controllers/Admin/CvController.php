<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Cv;
use Illuminate\Http\Request;

class CvController extends Controller
{
    public function edit()
    {
        return view('admin.cv.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf|max:'.Cv::MAX_KB,
        ], [
            'cv.required' => 'Sélectionnez un fichier.',
            'cv.mimes' => 'Le CV doit être un PDF.',
            'cv.max' => 'Le fichier est trop lourd : '.round(Cv::MAX_KB / 1024, 1).' Mo maximum.',
        ]);

        Cv::store($request->file('cv'));

        return redirect()->route('admin.cv.edit')
            ->with('success', '✅ CV mis en ligne avec succès !');
    }

    public function destroy()
    {
        Cv::delete();

        return redirect()->route('admin.cv.edit')
            ->with('success', '✅ CV retiré du site.');
    }
}
