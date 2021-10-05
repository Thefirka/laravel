<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewArticleRequest;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function allCurrentUserArticles()
    {
        if (Auth::user() != null) {

            $currentUser = Auth::user()->id;
            $articles = Article::where('user_id', '=', "$currentUser")->get();
            return view('currentUserArticles', ['articles' => $articles]);
        } else {
            return redirect('/login');
        }
    }

    public function allArticles()
    {

        $articles = Article::paginate(2);

        return view('home', [ 'articles' => $articles ]);
    }

    public function newArticle()
    {
        if (Auth::user() != null) {
            return view('newArticle');
        } else {
            return redirect('/login');
        }
    }

    public function createArticle(NewArticleRequest $articleRequest)
    {
        $user = Auth::user();
        $user->articles()->create([
            'title'     => $articleRequest->title,
            'body'      => $articleRequest->body,
        ]);
        return redirect(route('home'));
    }
    public function showArticle($slug)
    {
        $article = Article::where('title', '=', "$slug")->first();
        if ($article != null) {
            return view('Article', ['article' => $article]);
        } else {
           return redirect('/');
        }
    }
    public function loadMyNextArticle($slug)
    {
        $currentArticle = Article::where('title', '=', "$slug")->first();
        $currentArticleTimestamp = ($currentArticle->created_at->timestamp);

        $currentUser = Auth::user()->id;
        $articles = Article::where('user_id', '=', "$currentUser")->get();
        foreach ($articles as $article) {
            if ($article->created_at->timestamp > $currentArticleTimestamp) {
                return redirect(route('article', $article->title));
            }
        }
        return redirect(route('article', $currentArticle->title));
    }
    public function loadMyPreviousArticle($slug)
    {
        $currentArticle = Article::where('title', '=', "$slug")->first();
        $currentArticleTimestamp = ($currentArticle->created_at->timestamp);

        $currentUser = Auth::user()->id;
        $articles = Article::where('user_id', '=', "$currentUser")->get()->all();
        $articles = array_reverse($articles);
        foreach ($articles as $article) {
            if ($article->created_at->timestamp < $currentArticleTimestamp) {
                return redirect(route('article', $article->title));
            }
        }
        return redirect(route('article', $currentArticle->title));
    }
}
