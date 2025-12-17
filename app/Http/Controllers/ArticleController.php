<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function show($id)
    {
        $article = Article::where('en_ligne', 1)
            ->with(['editeur', 'rythme', 'accessibilite', 'conclusion', 'avis.user', 'likes'])
            ->findOrFail($id);

        return view('article.show', compact('article'));
    }
}