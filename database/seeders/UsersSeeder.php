<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Rythme;
use App\Models\Accessibilite;
use App\Models\Conclusion;
use App\Notifications\NewArticlePublished;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $hash = Hash::make('azerty');
        $faker = Factory::create('fr_FR');

        // 0. Catégories
        $rythme = Rythme::firstOrCreate(['texte' => 'Lent'], ['texte' => 'Lent']);
        $access = Accessibilite::firstOrCreate(['texte' => 'Facile'], ['texte' => 'Facile']);
        $concl = Conclusion::firstOrCreate(['texte' => 'Ouverte'], ['texte' => 'Ouverte']);

        // 1. CRÉATION DE "LES INROCKS" (Ton compte de test)
        $inrocks = User::firstOrCreate(
            ['email' => 'inrocks@gmail.com'],
            [
                'name' => "Les Inrocks",
                'email_verified_at' => now(),
                'password' => $hash,
            ]
        );

        $this->command->info('Compte Inrocks prêt.');

        // 2. CRÉATION DES USERS LAMBDA
        // On crée user1 spécifiquement pour le scénario
        $user1 = User::factory()->create([
            'name' => 'John Lennon', // On lui donne un nom cool
            'email' => "user1@gmail.com",
            'email_verified_at' => now(),
            'password' => $hash,
        ]);

        // On crée les 49 autres
        for ($i = 2; $i <= 50; $i++) {
            User::factory()->create([
                'name' => 'user' . $i,
                'email' => "user{$i}@gmail.com",
                'password' => $hash,
            ]);
        }

        // 3. LE SCÉNARIO "5 NOTIFICATIONS"

        // A. Inrocks s'abonne à John Lennon (user1)
        $inrocks->suivis()->attach($user1->id);

        // B. John Lennon publie 5 articles d'un coup !
        $this->command->info('Génération des 5 articles et envoi des notifs...');

        for ($j = 1; $j <= 5; $j++) {
            $article = Article::create([
                'titre' => "Article Exclusif N°" . $j, // Titres différents
                'resume' => "Voici le résumé de l'actualité numéro " . $j,
                'texte' => $faker->paragraph(3),
                'image' => 'articles/default.jpg',
                'media' => 'articles/default.mp3',
                'rythme_id' => $rythme->id,
                'accessibilite_id' => $access->id,
                'conclusion_id' => $concl->id,
                'user_id' => $user1->id, // C'est user1 qui écrit
                'en_ligne' => true,
            ]);

            // C. Envoi de la notification pour chaque article
            // Comme Inrocks suit user1, on notifie Inrocks
            Notification::send($inrocks, new NewArticlePublished($article));
        }

        $this->command->info('TERMINE : Connecte-toi sur inrocks@gmail.com pour voir les 5 notifs !');
    }
}