<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        // Valider les données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10|max:5000'
        ]);

        // Sauvegarder le message dans un fichier pour consultation
        $this->saveContactToFile($validated);

        // Envoyer l'email au propriétaire du site
        try {
            Mail::send('emails.contact', $validated, function ($message) use ($validated) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to(env('MAIL_TO_ADDRESS', 'admin@marathon.com'))
                    ->replyTo($validated['email'], $validated['nom'])
                    ->subject('Nouveau message de contact - ' . $validated['nom']);
            });
        } catch (\Exception $e) {
            Log::error('Erreur envoi email contact: ' . $e->getMessage());
        }

        // Retourner avec un message de succès
        return redirect()->route('contact')->with('success', 'Merci ! Votre message a été reçu avec succès. Nous vous répondrons bientôt.');
    }

    private function saveContactToFile($data)
    {
        $contactsDir = storage_path('app/contacts');

        // Créer le répertoire s'il n'existe pas
        if (!is_dir($contactsDir)) {
            mkdir($contactsDir, 0755, true);
        }

        // Créer un nom de fichier unique avec timestamp
        $filename = $contactsDir . '/' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.json';

        // Ajouter le timestamp
        $data['received_at'] = now()->toDateTimeString();

        // Sauvegarder les données en JSON
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

