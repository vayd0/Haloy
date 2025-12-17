<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function mesArticles() {
        return $this->hasMany(Article::class, "user_id");
    }

    public function avis() {
        return $this->hasMany(Avis::class, "user_id");
    }

    public function likes() {
        // On ajoute withPivot pour que Laravel récupère aussi la colonne 'nature' (ticket #9)
        return $this->belongsToMany(Article::class, 'likes')->withPivot('nature');
    }

    public function suivis() {
        return $this->belongsToMany(User::class, 'suivis', 'suiveur_id', 'suivi_id');
    }

    public function suiveurs() {
        return $this->belongsToMany(User::class, 'suivis', 'suivi_id', 'suiveur_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
