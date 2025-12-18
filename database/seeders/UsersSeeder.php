<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On prépare le mot de passe une seule fois pour gagner du temps
        $hash = Hash::make('azerty');

        // 1. CRÉATION DE "LES INROCKS" (Ton compte principal auteur)
        // On stocke l'utilisateur dans la variable $inrocks pour l'utiliser plus bas
        $inrocks = User::factory()->create([
            'name' => "Les Inrocks",
            'email' => "inrocks@gmail.com",
            'email_verified_at' => now(),
            'password' => $hash,
        ]);

        $this->command->info('Utilisateur "inrocks@gmail.com" créé !');

        // 2. CRÉATION DES 50 FOLLOWERS (user1 à user50)
        for ($i = 1; $i <= 50; $i++) {
            $follower = User::factory()->create([
                'name' => 'user' . $i,
                'email' => "user{$i}@gmail.com", // Syntaxe propre pour user1@gmail.com
                'email_verified_at' => now(),
                'password' => $hash,
            ]);

            // --- L'ASTUCE POUR TON TEST ---
            // On force TOUS ces utilisateurs à s'abonner à "inrocks"
            $follower->suivis()->attach($inrocks->id);
        }

        $this->command->info('50 utilisateurs créés et abonnés à Inrocks.');

        // 3. (OPTIONNEL) BRUIT DE FOND : Des abonnements aléatoires entre les autres
        // Pour que ça fasse "vrai réseau social"
        $faker = Factory::create('fr_FR');
        $allUsers = User::where('id', '!=', $inrocks->id)->get(); // Tous sauf inrocks

        foreach($allUsers as $user) {
            // Chaque user suit entre 0 et 5 autres personnes au hasard (sauf inrocks qu'il suit déjà)
            $randomUsers = $allUsers->random(rand(0, 5))->pluck('id');
            $user->suivis()->syncWithoutDetaching($randomUsers);
        }
    }
}