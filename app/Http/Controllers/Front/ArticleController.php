<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public const PREVIOUSARTICLE = 'My Previous Article';
    public const NEXTARTICLE     = 'My Next Article';
    public function allCurrentUserArticles()
    {
        if (Auth::user() != null) {

            $currentUser = Auth::user()->id;
            $articles = Article::where('user_id', '=', "$currentUser")->get();

            return view('currentUserArticles', [ 'articles' => $articles ] );
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
        return view('newArticle');
    }

    public function createArticle(ArticleRequest $articleRequest)
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
        $article  = Article::where('title', '=', "$slug")->first();
        if (Comment::exists()) {
            $comments = $article->comments()->get();
            dd($comments);
        } else {
            $comments = [];
        }
        if ($article) {
            return view('article', [ 'article' => $article, 'comments' => $comments ]);
        } else {
           return redirect('/');
        }
    }

    public function loadArticle($slug, Request $request)
    {
        $loadArticle = $request->post('LoadArticle');

        if ($loadArticle === self::PREVIOUSARTICLE) {

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
        } elseif ($loadArticle === self::NEXTARTICLE) {

            $currentArticle = Article::where('title', '=', "$slug")->first();
            $currentArticleTimestamp = ($currentArticle->created_at->timestamp);
            $currentUser = Auth::user()->id;
            $articles = Article::where('user_id', '=', "$currentUser")->get();

            foreach ($articles as $article) {
                if ($article->created_at->timestamp > $currentArticleTimestamp) {

                    return redirect(route('article', $article->title));
                }
            }
        }

        return redirect(route('article', $slug));
    }

    public function deleteArticle($slug)
    {
        Article::where('title', $slug)->delete();
        return redirect(route('articles'));
    }
}
