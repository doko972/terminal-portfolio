<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            'g-recaptcha-response' => 'required',
        ], [
            // Messages personnalisés
            'nom.required' => 'Le nom est obligatoire',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'message.required' => 'Le message est obligatoire',
            'message.min' => 'Le message doit contenir au moins 10 caractères',
            'g-recaptcha-response.required' => 'Veuillez cocher la case de vérification',
        ]);

        if (! $this->recaptchaPasses($request)) {
            return redirect()->back()
                ->withErrors(['g-recaptcha-response' => 'La vérification reCAPTCHA a échoué. Veuillez réessayer.'])
                ->withInput();
        }

        try {
            Mail::to(config('services.contact.to'))->send(new ContactMail($validated));
        } catch (\Throwable $e) {
            // Le détail reste dans les logs : ne jamais l'exposer au visiteur,
            // il contient hôte SMTP, port et parfois des identifiants.
            Log::error('Erreur envoi contact', ['exception' => $e]);

            return redirect()->back()
                ->with('error', '✗ Erreur lors de l\'envoi du message. Merci de réessayer plus tard.')
                ->withInput();
        }

        return redirect()->back()->with('success',
            '✓ Message envoyé avec succès ! Je vous répondrai dans les plus brefs délais.');
    }

    /**
     * Valide le jeton reCAPTCHA auprès de Google.
     *
     * Une erreur réseau vers Google ne doit pas passer pour un captcha valide :
     * en cas d'échec de l'appel, on refuse.
     */
    private function recaptchaPasses(Request $request): bool
    {
        $secretKey = config('services.recaptcha.secret_key');

        if (blank($secretKey)) {
            Log::error('reCAPTCHA : RECAPTCHA_SECRET_KEY absent de la configuration.');

            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secretKey,
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ]);
        } catch (\Throwable $e) {
            Log::error('reCAPTCHA : appel à siteverify impossible', ['exception' => $e]);

            return false;
        }

        return $response->successful() && $response->json('success') === true;
    }
}
