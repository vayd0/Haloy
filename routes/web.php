<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name("accueil");

Route::get('/contact', function () {
    return view('contact');
})->name("contact");

Route::get('/test-vite', function () {
    return view('test-vite');
})->name("test-vite");

// Routes pour l'affichage des articles (accueil et filtres)
Route::get('/home', [ArticleController::class, 'index'])->name("home");
Route::get('/articles/filter/{type}/{id}', [ArticleController::class, 'filterByCharacteristic'])->name('articles.filter');

// Routes nécessitant une authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
});
