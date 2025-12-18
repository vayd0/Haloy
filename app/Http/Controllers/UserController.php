<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Affiche la page de profil avec les articles publiés, notifications et suggestions
    public function profile()
    {
        $user = Auth::user();
        $articles = $user->mesArticles()->where('en_ligne', true)->get();

        // Récupérer les notifications non lues
        $notifications = $user->unreadNotifications ?? collect();

        // Trouver des utilisateurs similaires
        try {
            $suggestedUsers = $this->findSimilarUsers($user);
        } catch (\Exception $e) {
            $suggestedUsers = collect();
        }

        return view('users.profile', compact('articles', 'notifications', 'suggestedUsers'));
    }

    /**
     * Trouve des utilisateurs avec des goûts similaires
     */
    private function findSimilarUsers(User $user)
    {
        // Récupérer les articles aimés par l'utilisateur avec leurs caractéristiques
        $likedArticles = $user->likes()
            ->wherePivot('nature', true)
            ->with(['rythme', 'accessibilite', 'conclusion'])
            ->get();

        if ($likedArticles->isEmpty()) {
            return collect();
        }

        // Compter les occurrences de chaque caractéristique
        $rythmes = $likedArticles->pluck('rythme_id')->filter()->countBy();
        $accessibilites = $likedArticles->pluck('accessibilite_id')->filter()->countBy();
        $conclusions = $likedArticles->pluck('conclusion_id')->filter()->countBy();

        // Trouver la caractéristique la plus fréquente
        $mostFrequentRythme = $rythmes->sortDesc()->keys()->first();
        $mostFrequentAccessibilite = $accessibilites->sortDesc()->keys()->first();
        $mostFrequentConclusion = $conclusions->sortDesc()->keys()->first();

        // Récupérer les IDs des utilisateurs déjà suivis
        $followedIds = $user->suivis()->pluck('users.id')->toArray();
        $followedIds[] = $user->id; // Exclure l'utilisateur lui-même

        // Trouver des utilisateurs qui aiment des articles avec les mêmes caractéristiques
        $similarUsers = User::whereHas('likes', function ($query) use ($mostFrequentRythme, $mostFrequentAccessibilite, $mostFrequentConclusion) {
                $query->wherePivot('nature', true)
                    ->whereHas('article', function ($subQuery) use ($mostFrequentRythme, $mostFrequentAccessibilite, $mostFrequentConclusion) {
                        $subQuery->where(function ($q) use ($mostFrequentRythme, $mostFrequentAccessibilite, $mostFrequentConclusion) {
                            if ($mostFrequentRythme) {
                                $q->orWhere('rythme_id', $mostFrequentRythme);
                            }
                            if ($mostFrequentAccessibilite) {
                                $q->orWhere('accessibilite_id', $mostFrequentAccessibilite);
                            }
                            if ($mostFrequentConclusion) {
                                $q->orWhere('conclusion_id', $mostFrequentConclusion);
                            }
                        });
                    });
            })
            ->whereNotIn('id', $followedIds)
            ->withCount(['likes' => function ($query) use ($mostFrequentRythme, $mostFrequentAccessibilite, $mostFrequentConclusion) {
                $query->wherePivot('nature', true)
                    ->whereHas('article', function ($subQuery) use ($mostFrequentRythme, $mostFrequentAccessibilite, $mostFrequentConclusion) {
                        $subQuery->where(function ($q) use ($mostFrequentRythme, $mostFrequentAccessibilite, $mostFrequentConclusion) {
                            if ($mostFrequentRythme) {
                                $q->orWhere('rythme_id', $mostFrequentRythme);
                            }
                            if ($mostFrequentAccessibilite) {
                                $q->orWhere('accessibilite_id', $mostFrequentAccessibilite);
                            }
                            if ($mostFrequentConclusion) {
                                $q->orWhere('conclusion_id', $mostFrequentConclusion);
                            }
                        });
                    });
            }])
            ->orderByDesc('likes_count')
            ->limit(5)
            ->get();

        return $similarUsers;
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

    /**
     * Marque toutes les notifications comme lues
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return back();
    }
}
