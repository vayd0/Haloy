<?php

namespace App\Http\Controllers;

use App\Models\Accessibilite;
use App\Models\Conclusion;
use App\Models\Rythme;
use App\Models\Article;

class CaracteristiqueController extends Controller
{
    public function rythme($id)
    {
        $rythme = Rythme::findOrFail($id);

        $articles = Article::where('en_ligne', 1)
            ->where('rythme_id', $id)
            ->with(['editeur', 'rythme', 'accessibilite', 'conclusion'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('caracteristique.liste', [
            'articles' => $articles,
            'caracteristique' => $rythme,
            'type' => 'Rythme'
        ]);
    }

    public function accessibilite($id)
    {
        $accessibilite = Accessibilite::findOrFail($id);

        $articles = Article::where('en_ligne', 1)
            ->where('accessibilite_id', $id)
            ->with(['editeur', 'rythme', 'accessibilite', 'conclusion'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('caracteristique.liste', [
            'articles' => $articles,
            'caracteristique' => $accessibilite,
            'type' => 'Accessibilité'
        ]);
    }

    public function conclusion($id)
    {
        $conclusion = Conclusion::findOrFail($id);

        $articles = Article::where('en_ligne', 1)
            ->where('conclusion_id', $id)
            ->with(['editeur', 'rythme', 'accessibilite', 'conclusion'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('caracteristique.liste', [
            'articles' => $articles,
            'caracteristique' => $conclusion,
            'type' => 'Conclusion'
        ]);
    }
}