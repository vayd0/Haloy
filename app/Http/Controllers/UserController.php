<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $articles = $user->mesArticles()->where('en_ligne', false)->get();

        return view('user.profile', compact('articles'));
    }
}
