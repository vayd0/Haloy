<?php

namespace App\Http\Controllers;

use App\Models\Article;

class AccueilController extends Controller
{
    public function index()
    {
        // Afficher seulement les 3 articles les plus récents
        $articles = Article::where('en_ligne', 1)
            ->with(['editeur', 'rythme', 'accessibilite', 'conclusion'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('welcome', compact('articles'));
    }
}