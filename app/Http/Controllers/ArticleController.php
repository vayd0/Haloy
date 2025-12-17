<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    // Affiche la liste des articles actifs (et ceux de l'utilisateur connecté)
    public function index()
    {
        $query = Article::where('en_ligne', true);

        if (Auth::check()) {
            $query->orWhere('user_id', Auth::id());
        }

        $articles = $query->get();

        return view('home', compact('articles'));
    }

    // Filtre les articles par caractéristique (rythme, accessibilité, conclusion)
    public function filterByCharacteristic($type, $id)
    {
        $query = Article::where('en_ligne', true);

        if (Auth::check()) {
            $query->orWhere('user_id', Auth::id());
        }

        // Apply filter on the base query
        $articles = $query->get()->filter(function ($article) use ($type, $id) {
            return match ($type) {
                'rythme' => $article->rythme_id == $id,
                'accessibilite' => $article->accessibilite_id == $id,
                'conclusion' => $article->conclusion_id == $id,
                default => false,
            };
        });

        return view('home', compact('articles'));
    }
}
