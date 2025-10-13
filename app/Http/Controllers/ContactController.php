<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    /**
     * Affiche le formulaire de contact
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Traite l'envoi du formulaire
     */
    public function send(Request $request)
    {
        // Validation simple et efficace
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'sujet' => 'nullable|string|max:200',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'nom.required' => 'Le nom est requis',
            'email.required' => 'L\'email est requis',
            'email.email' => 'L\'email doit être valide',
            'message.required' => 'Le message est requis',
            'message.min' => 'Le message doit contenir au moins 10 caractères',
        ]);

        try {
            // Envoi de l'email
            Mail::to(config('mail.from.address')) // Votre email
                ->send(new ContactMail($validated));

            return back()->with('success', '> Message envoyé avec succès ! Je vous répondrai rapidement._');
            
        } catch (\Exception $e) {
            return back()
                ->with('error', '> Erreur lors de l\'envoi. Veuillez réessayer._')
                ->withInput();
        }
    }
}