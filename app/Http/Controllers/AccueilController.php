<?php

namespace App\Http\Controllers;

use App\Models\Article;

class AccueilController extends Controller
{
    public function index()
    {
        $articles = Article::where('en_ligne', 1)
            ->with(['editeur', 'rythme', 'accessibilite', 'conclusion'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('welcome', compact('articles'));
    }
}