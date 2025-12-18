<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Notifications\NewArticlePublished; // Import de ta notification
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ArticleController extends Controller
{
    // Affiche la liste des articles actifs
    public function index()
    {
        $query = Article::where('en_ligne', true);

        if (Auth::check()) {
            $query->orWhere('user_id', Auth::id());
        }

        $rythmes = \App\Models\Rythme::all();
        $accessibilites = \App\Models\Accessibilite::all();
        $conclusions = \App\Models\Conclusion::all();

        $articles = $query
            ->with(['editeur', 'rythme', 'accessibilite', 'conclusion'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('article.all', compact('articles', 'rythmes', 'accessibilites', 'conclusions'));
    }

    // Filtre les articles
    public function filterByCharacteristic($type, $id)
    {
        $query = Article::where('en_ligne', true);

        if (Auth::check()) {
            $query->orWhere('user_id', Auth::id());
        }

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
        if (!$article->en_ligne && (!Auth::check() || Auth::id() !== $article->user_id)) {
            abort(404);
        }

        $article->increment('nb_vues');
        $similarArticles = $this->getSimilarArticles($article);
        $likesCount = $article->likes()->wherePivot('nature', true)->count();
        $dislikesCount = $article->likes()->wherePivot('nature', false)->count();

        $userLikeStatus = null;
        if (Auth::check()) {
            $userLike = $article->likes()->wherePivot('user_id', Auth::id())->first();
            if ($userLike) {
                $userLikeStatus = $userLike->pivot->nature;
            }
        }

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

    public function like(Article $article)
    {
        if (!Auth::check()) abort(401);
        DB::table('likes')->updateOrInsert(
            ['user_id' => Auth::id(), 'article_id' => $article->id],
            ['nature' => true]
        );
        return redirect()->back()->with('success', 'Article aimé!');
    }

    public function dislike(Article $article)
    {
        if (!Auth::check()) abort(401);
        DB::table('likes')->updateOrInsert(
            ['user_id' => Auth::id(), 'article_id' => $article->id],
            ['nature' => false]
        );
        return redirect()->back()->with('success', 'Avis enregistré!');
    }

    public function unlike(Article $article)
    {
        if (!Auth::check()) abort(401);
        DB::table('likes')->where('user_id', Auth::id())->where('article_id', $article->id)->delete();
        return redirect()->back()->with('success', 'Avis retiré!');
    }

    public function addComment(Request $request, Article $article)
    {
        if (!Auth::check()) abort(401);
        $request->validate(['contenu' => 'required|string|max:1000']);
        $article->avis()->create(['user_id' => Auth::id(), 'contenu' => $request->contenu]);
        return redirect()->back()->with('success', 'Commentaire ajouté!');
    }

    public function edit(Article $article)
    {
        if (Auth::id() !== $article->user_id) abort(403);
        $rythmes = \App\Models\Rythme::all();
        $accessibilites = \App\Models\Accessibilite::all();
        $conclusions = \App\Models\Conclusion::all();

        return view('article.edit', compact('article', 'rythmes', 'accessibilites', 'conclusions'));
    }

    // UPDATE : Avec Notification
    public function update(Request $request, Article $article)
    {
        if (Auth::id() !== $article->user_id) abort(403);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'resume' => 'required|string|max:500',
            'texte' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'media' => 'nullable|file|mimes:mp3,wav|max:51200',
            'rythme_id' => 'required|exists:rythmes,id',
            'accessibilite_id' => 'required|exists:accessibilites,id',
            'conclusion_id' => 'required|exists:conclusions,id',
            'en_ligne' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }
        if ($request->hasFile('media')) {
            $validated['media'] = $request->file('media')->store('articles', 'public');
        }

        $wasOffline = !$article->en_ligne;
        $willBeOnline = $request->has('en_ligne') && $request->input('en_ligne');

        $article->update($validated);

        // LOGIQUE NOTIFICATION UPDATE
        if ($wasOffline && $willBeOnline) {
            $suiveurs = $article->editeur->suiveurs;
            // On utilise la classe importée en haut
            Notification::send($suiveurs, new NewArticlePublished($article));
        }

        return redirect()->route('article.show', $article)->with('success', 'Article mis à jour avec succès!');
    }

    public function create()
    {
        $rythmes = \App\Models\Rythme::all();
        $accessibilites = \App\Models\Accessibilite::all();
        $conclusions = \App\Models\Conclusion::all();
        return view('article.create', compact('rythmes', 'accessibilites', 'conclusions'));
    }

    // STORE : Avec Notification
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'resume' => 'required|string|max:500',
            'texte' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
            'media' => 'required|mimes:mp3,wav,m4a|max:20480',
            'rythme_id' => 'required|exists:rythmes,id',
            'accessibilite_id' => 'required|exists:accessibilites,id',
            'conclusion_id' => 'required|exists:conclusions,id',
            'en_ligne' => 'boolean',
        ]);

        $imagePath = $request->file('image')->store('articles', 'public');
        $mediaPath = $request->file('media')->store('articles', 'public');

        $article = Article::create([
            'titre' => $validated['titre'],
            'resume' => $validated['resume'],
            'texte' => $validated['texte'],
            'image' => $imagePath,
            'media' => $mediaPath,
            'rythme_id' => $validated['rythme_id'],
            'accessibilite_id' => $validated['accessibilite_id'],
            'conclusion_id' => $validated['conclusion_id'],
            'user_id' => Auth::id(),
            'en_ligne' => $request->has('en_ligne') ? $request->input('en_ligne') : false,
        ]);

        // LOGIQUE NOTIFICATION STORE
        if ($article->en_ligne) {
            $suiveurs = Auth::user()->suiveurs;
            // On utilise la classe importée
            Notification::send($suiveurs, new NewArticlePublished($article));
        }

        return redirect()->route('accueil')->with('success', 'Article créé avec succès!');
    }
}