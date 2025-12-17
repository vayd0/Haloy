<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\CaracteristiqueController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/', [AccueilController::class, 'index'])->name("accueil");

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
// Article Routes
Route::get('/article/{article}', [ArticleController::class, 'show'])->name('article.show');
Route::post('/article/{article}/like', [ArticleController::class, 'like'])->name('article.like')->middleware('auth');
Route::post('/article/{article}/dislike', [ArticleController::class, 'dislike'])->name('article.dislike')->middleware('auth');
Route::delete('/article/{article}/unlike', [ArticleController::class, 'unlike'])->name('article.unlike')->middleware('auth');
Route::post('/article/{article}/comment', [ArticleController::class, 'addComment'])->name('article.comment')->middleware('auth');

// Caracteristique Routes
Route::get('/rythme/{id}', [CaracteristiqueController::class, 'rythme'])->name('rythme.articles');
Route::get('/accessibilite/{id}', [CaracteristiqueController::class, 'accessibilite'])->name('accessibilite.articles');
Route::get('/conclusion/{id}', [CaracteristiqueController::class, 'conclusion'])->name('conclusion.articles');