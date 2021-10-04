<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewArticleRequest;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class NewArticle extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function view()
    {
        return view('newArticle');
    }
    public function post(NewArticleRequest $articleRequest)
    {
        $userId = Auth::user()->id;
        Article::create([
            'title' => $articleRequest->title,
            'body' => $articleRequest->body,
            'author_id' => $userId
        ]);
        return redirect(route('homepage'));
    }
}
