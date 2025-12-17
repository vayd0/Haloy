<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    // Afficher l'article
    public function show(Article $article)
    {
        // Vérifier visibilité (article publié ou auteur connecté)
        if (!$article->en_ligne && (!Auth::check() || Auth::id() !== $article->user_id)) {
            abort(404);
        }

        // Incrémenter compteur vues
        $article->increment('nb_vues');

        // Articles similaires
        $similarArticles = $this->getSimilarArticles($article);

        // Compter likes et dislikes
        $likesCount = $article->likes()->wherePivot('nature', true)->count();
        $dislikesCount = $article->likes()->wherePivot('nature', false)->count();

        // Récupérer le vote actuel de l'utilisateur
        $userLikeStatus = null;
        if (Auth::check()) {
            $userLike = $article->likes()->wherePivot('user_id', Auth::id())->first();
            if ($userLike) {
                $userLikeStatus = $userLike->pivot->nature;
            }
        }

        // Récupérer avis paginés
        $avis = $article->avis()->with('user')->latest()->paginate(5);

        return view('article.show', [
            'article' => $article,
            'similarArticles' => $similarArticles,
            'likesCount' => $likesCount,
            'dislikesCount' => $dislikesCount,
            'userLikeStatus' => $userLikeStatus,
            'avis' => $avis,
        ]);
    }

    // Articles similaires (rythme, accessibilité, conclusion)
    private function getSimilarArticles(Article $article, $limit = 4)
    {
        return Article::where('id', '!=', $article->id)
            ->where('en_ligne', true)
            ->where(function ($query) use ($article) {
                $query->where('rythme_id', $article->rythme_id)
                    ->orWhere('accessibilite_id', $article->accessibilite_id)
                    ->orWhere('conclusion_id', $article->conclusion_id);
            })
            ->with(['editeur', 'rythme', 'accessibilite', 'conclusion'])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    // Aimer l'article
    public function like(Article $article)
    {
        if (!Auth::check()) {
            abort(401);
        }

        DB::table('likes')->updateOrInsert(
            ['user_id' => Auth::id(), 'article_id' => $article->id],
            ['nature' => true]
        );

        return redirect()->back()->with('success', 'Article aimé!');
    }

    // Ne pas aimer l'article
    public function dislike(Article $article)
    {
        if (!Auth::check()) {
            abort(401);
        }

        DB::table('likes')->updateOrInsert(
            ['user_id' => Auth::id(), 'article_id' => $article->id],
            ['nature' => false]
        );

        return redirect()->back()->with('success', 'Avis enregistré!');
    }

    // Retirer le vote
    public function unlike(Article $article)
    {
        if (!Auth::check()) {
            abort(401);
        }

        DB::table('likes')
            ->where('user_id', Auth::id())
            ->where('article_id', $article->id)
            ->delete();

        return redirect()->back()->with('success', 'Avis retiré!');
    }

    // Ajouter un commentaire
    public function addComment(Request $request, Article $article)
    {
        if (!Auth::check()) {
            abort(401);
        }

        $request->validate(['contenu' => 'required|string|max:1000']);

        $article->avis()->create([
            'user_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        return redirect()->back()->with('success', 'Commentaire ajouté!');
    }

    // Afficher le formulaire de modification
    public function edit(Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur
        if (Auth::id() !== $article->user_id) {
            abort(403);
        }

        // Récupérer les caractéristiques
        $rythmes = \App\Models\Rythme::all();
        $accessibilites = \App\Models\Accessibilite::all();
        $conclusions = \App\Models\Conclusion::all();

        return view('article.edit', [
            'article' => $article,
            'rythmes' => $rythmes,
            'accessibilites' => $accessibilites,
            'conclusions' => $conclusions,
        ]);
    }

    // Mettre à jour l'article
    public function update(Request $request, Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur
        if (Auth::id() !== $article->user_id) {
            abort(403);
        }

        // Valider les données
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'resume' => 'required|string|max:500',
            'texte' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'media' => 'nullable|mimes:mp3,wav,m4a,mp4,aac|max:20480',
            'rythme_id' => 'required|exists:rythmes,id',
            'accessibilite_id' => 'required|exists:accessibilites,id',
            'conclusion_id' => 'required|exists:conclusions,id',
            'en_ligne' => 'boolean',
        ]);

        // Gérer l'upload de l'image si présente
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
            $validated['image'] = $imagePath;
        }

        // Gérer l'upload du média si présent
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('articles', 'public');
            $validated['media'] = $mediaPath;
        }

        // Mettre à jour l'article
        $article->update($validated);

        return redirect()->route('article.show', $article)->with('success', 'Article mis à jour avec succès!');
    }

    // Afficher le formulaire de création
    public function create()
    {
        // Récupérer les caractéristiques
        $rythmes = \App\Models\Rythme::all();
        $accessibilites = \App\Models\Accessibilite::all();
        $conclusions = \App\Models\Conclusion::all();

        return view('article.create', [
            'rythmes' => $rythmes,
            'accessibilites' => $accessibilites,
            'conclusions' => $conclusions,
        ]);
    }

    // Enregistrer l'article
    public function store(Request $request)
    {
        // Valider les données
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'resume' => 'required|string|max:500',
            'texte' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
            'media' => 'required|mimes:mp3,wav,m4a|max:20480',
            'rythme_id' => 'required|exists:rythmes,id',
            'accessibilite_id' => 'required|exists:accessibilites,id',
            'conclusion_id' => 'required|exists:conclusions,id',
        ]);

        // Stocker les fichiers
        $imagePath = $request->file('image')->store('articles', 'public');
        $mediaPath = $request->file('media')->store('articles', 'public');

        // Créer l'article
        Article::create([
            'titre' => $validated['titre'],
            'resume' => $validated['resume'],
            'texte' => $validated['texte'],
            'image' => $imagePath,
            'media' => $mediaPath,
            'rythme_id' => $validated['rythme_id'],
            'accessibilite_id' => $validated['accessibilite_id'],
            'conclusion_id' => $validated['conclusion_id'],
            'user_id' => Auth::id(),
            'en_ligne' => false,
        ]);

        return redirect()->route('accueil')->with('success', 'Article créé avec succès!');
    }
}
