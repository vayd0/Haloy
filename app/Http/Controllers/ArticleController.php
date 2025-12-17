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
        if (!Auth::check()) abort(401);

        DB::table('likes')->updateOrInsert(
            ['user_id' => Auth::id(), 'article_id' => $article->id],
            ['nature' => true]
        );

        return redirect()->back()->with('success', 'Article aimé!');
    }

    // Ne pas aimer l'article
    public function dislike(Article $article)
    {
        if (!Auth::check()) abort(401);

        DB::table('likes')->updateOrInsert(
            ['user_id' => Auth::id(), 'article_id' => $article->id],
            ['nature' => false]
        );

        return redirect()->back()->with('success', 'Avis enregistré!');
    }

    // Retirer le vote
    public function unlike(Article $article)
    {
        if (!Auth::check()) abort(401);

        DB::table('likes')
            ->where('user_id', Auth::id())
            ->where('article_id', $article->id)
            ->delete();

        return redirect()->back()->with('success', 'Avis retiré!');
    }

    // Ajouter un commentaire
    public function addComment(Request $request, Article $article)
    {
        if (!Auth::check()) abort(401);

        $request->validate(['contenu' => 'required|string|max:1000']);

        $article->avis()->create([
            'user_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        return redirect()->back()->with('success', 'Commentaire ajouté!');
    }
}

