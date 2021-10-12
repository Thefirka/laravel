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
        if (Comment::where('article_id', '=', "$article->id")->get()->all()) {
            $article_id = $article->id;
            $comments = Comment::where('article_id', '=', "$article->id")->get()->toTree();
            $recursion = function ($comments, $article_id) use (&$recursion) {
                foreach ($comments as $comment) {
                    static $commentShow;
                    static $rightPosition;
                     $commentShow .= " <div style=\"width:250px;height:50px;border:1px solid #000;\">{$comment->body}</div>
<br>
    <form action=\"".route('newComment') ."\" method='post'>
    <input type=\"hidden\" name=\"_token\" value=\"". csrf_token()."\"/>
    Write reply <input type=\"text\" name=\"body\">
    <input type=\"hidden\" name=\"article_id\" value=\"$article_id\"/>
    <input type=\"hidden\" name=\"comment_id\" value=\"$comment->id\"/>
    <input type=\"submit\">
    </form>
    </div>
    <br>";

                    if ($comment->parent && (!$comment->children->all())) {
                        $rightPosition = 250;
                        $nextLevel = "<div style = \"position:relative;"."left:$rightPosition".'px'.";\"top: 20px;\">";
                        $commentShow .= $nextLevel;
                    }
                    if ($comment->children->all()) {
                        $rightPosition += 250;
                        $nextLevel = "<div style = \"position:relative;"."left:$rightPosition".'px'.";\"top: 20px;\">";
                        $commentShow .= $nextLevel;
                        $recursion($comment->children, $article_id);
                    }
                }
                return $commentShow;
            };
            $commentShow = $recursion($comments, $article_id);
        } else {
            $commentShow = "";
        }
        if ($article) {
            return view('article', [ 'article' => $article, 'commentShow' => $commentShow ]);
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
