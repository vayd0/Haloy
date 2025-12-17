<?php

use App\Http\Controllers\ArticleController;
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

Route::get('/home', function () {
    return view('home');
})->name("home");

// Article Routes
Route::get('/article/{article}', [ArticleController::class, 'show'])->name('article.show');
Route::post('/article/{article}/like', [ArticleController::class, 'like'])->name('article.like')->middleware('auth');
Route::post('/article/{article}/dislike', [ArticleController::class, 'dislike'])->name('article.dislike')->middleware('auth');
Route::delete('/article/{article}/unlike', [ArticleController::class, 'unlike'])->name('article.unlike')->middleware('auth');
Route::post('/article/{article}/comment', [ArticleController::class, 'addComment'])->name('article.comment')->middleware('auth');
