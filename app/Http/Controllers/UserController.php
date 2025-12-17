<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Affiche la page de profil avec les articles en brouillon
    public function profile()
    {
        $user = Auth::user();
        $articles = $user->mesArticles()->where('en_ligne', false)->get();

        return view('user.profile', compact('articles'));
    }

    /**
     * Affiche le profil d'un utilisateur spécifique
     */
    public function show(User $user)
    {
        $user->load(['likes.rythme', 'suiveurs', 'suivis']);

        $rythmes = $user->likes->pluck('rythme.texte')->unique();

        return view('users.show', compact('user', 'rythmes'));
    }

    /**
     * Permet de suivre ou ne plus suivre un utilisateur
     */
    public function follow(User $user)
    {
        $visiteur = Auth::user();

        if ($visiteur->id !== $user->id) {
            $visiteur->suivis()->toggle($user->id);
        }

        return back(); // Retour à la page précédente
    }
}
