<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Important pour la file d'attente
use Illuminate\Notifications\Notification;

class NewArticlePublished extends Notification implements ShouldQueue
{
    use Queueable;

    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

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