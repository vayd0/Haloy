<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
})->name("accueil");

Route::get('/contact', function () {
    return view('contact');
})->name("contact");

Route::get('/test-vite', function () {
    return view('test-vite');
})->name("test-vite");

Route::get('/home', function () {
    return view('home');
})->name("home");

// Route pour afficher la page d'un utilisateur
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// Route pour l'action "Suivre" (nécessite d'être connecté)
Route::post('/users/{user}/follow', [UserController::class, 'follow'])
    ->middleware('auth')
    ->name('users.follow');

