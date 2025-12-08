<?php

namespace Database\Seeders;


use App\Models\Audience;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class FollowsSeeder  extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create('fr_FR');

        $users = User::all();
        foreach($users as $user) {
            $nbF = $faker->numberBetween(2, 10);
            $userIds = User::pluck('id');
            $userIdsSelected = $faker->randomElements($userIds, $nbF);
            $user->suivis()->attach($userIdsSelected);
        }
    }
}