<?php

use App\Http\Controllers\AccueilController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/contact', function () {
    return view('contact');
})->name("contact");

Route::get('/test-vite', function () {
    return view('test-vite');
})->name("test-vite");

Route::get('/home', function () {
    return view('home');
})->name("home");

Route::get('/', [AccueilController::class, 'index'])->name("accueil");
Route::get('/article/{id}', [App\Http\Controllers\ArticleController::class, 'show'])->name("article.show");