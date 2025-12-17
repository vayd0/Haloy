<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewArticlePublished extends Notification implements ShouldQueue
{
    use Queueable;

    // Article publié
    public $article;

    /**
     * Création de la notification
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Canaux de notification (base de données)
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Données stockées dans la base de données
     */
    public function toArray(object $notifiable): array
    {
        return [
            'article_id' => $this->article->id,
            'article_titre' => $this->article->titre,
            'article_resume' => $this->article->resume,
            'article_image' => $this->article->image,
            'auteur_id' => $this->article->user_id,
            'auteur_name' => $this->article->editeur->name,
        ];
    }
}

