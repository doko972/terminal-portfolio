<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
     * Traite l'envoi du formulaire de contact
     */
    public function send(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sujet' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
            'g-recaptcha-response' => 'required'
        ], [
            // Messages personnalisés
            'nom.required' => 'Le nom est obligatoire',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'message.required' => 'Le message est obligatoire',
            'message.min' => 'Le message doit contenir au moins 10 caractères',
            'g-recaptcha-response.required' => 'Veuillez cocher la case de vérification'
        ]);

        // Vérification du reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = config('services.recaptcha.secret_key');

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip()
            ]);

            $result = $response->json();

            if (!isset($result['success']) || !$result['success']) {
                return redirect()->back()
                    ->withErrors(['g-recaptcha-response' => 'La vérification reCAPTCHA a échoué. Veuillez réessayer.'])
                    ->withInput();
            }

            // Envoi de l'email
            Mail::to('david.grougi@gmail.com')->send(new ContactMail($validated));

            return redirect()->back()->with('success', 
                '✓ Message envoyé avec succès ! Je vous répondrai dans les plus brefs délais.');

        } catch (\Exception $e) {
            // Log de l'erreur pour debug
            \Log::error('Erreur envoi contact: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', '✗ Erreur lors de l\'envoi du message. Détails: ' . $e->getMessage())
                ->withInput();
        }
    }
}
