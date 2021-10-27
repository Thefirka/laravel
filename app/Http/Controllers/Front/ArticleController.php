<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Services\OpenWeatherApi\CurrentWeather;
use App\Services\OpenWeatherApi\IOpenWeather;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public const PREVIOUSARTICLE = 'My Previous Article';
    public const NEXTARTICLE     = 'My Next Article';

    public function allCurrentUserArticles()
    {
        $currentUserId = Auth::user()->id;
        $articles = Article::where('user_id', '=', $currentUserId)->get();

        return view('currentUserArticles', ['articles' => $articles]);
    }

    public function allArticles()
    {
        $articles = Article::paginate(10);

        return view('home', [ 'articles' => $articles ]);
    }

    public function newArticle()
    {
        return view('newArticle');
    }

    public function createArticle(ArticleRequest $articleRequest)
    {
        $user = Auth::user();
        $weather = CurrentWeather::getWeather();

        $user->articles()->create([
            'title'     => $articleRequest->title,
            'body'      => $articleRequest->body,
            'temperature' => $weather['temperature'],
            'weather_description' => $weather['weather_description']
        ]);

        return redirect(route('home'));
    }

    public function showArticle($slug)
    {
        $article  = Article::where('title', '=', "$slug")->first();

        if (Comment::where('article_id', '=', "$article->id")->get()->all()) {
            $article_id = $article->id;

            $comments = Comment::where('article_id', '=', $article->id)->get()->toTree();
            $recursion = function ($comments, $article_id) use (&$recursion) {
                foreach ($comments as $comment) {
                    $comment_id = $comment->id;

                    static $commentShow;
                    static $rightPosition;
                    $commentShow .= " <div style=\"width:250px;height:50px;border:1px solid #000;\">{$comment->body}</div>
<br>
    <form action=\"".route('newComment') ."\" method='post'>
    <input type=\"hidden\" name=\"_token\" value=\"". csrf_token()."\"/>
    Write reply <input type=\"text\" name=\"body\">
    <input type=\"hidden\" name=\"article_id\" value=\"$article_id\"/>
    <input type=\"hidden\" name=\"parent_id\" value=\"$comment_id\"/>
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

                    if (!$comment->parent && (!$comment->children->all())) {
                        $rightPosition = 0;
                        $nextLevel = "<div style = \"position:relative;"."left:$rightPosition".'px'.";\"top: 20px;\">";
                        $commentShow .= $nextLevel;
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

        switch ($loadArticle) {
            case self::PREVIOUSARTICLE:

                $currentArticle = Article::where('title', '=', "$slug")->first();
                $currentArticleDate = ($currentArticle->created_at);
                $currentUserId = Auth::user()->id;
                $articles = array_reverse(Article::where('user_id', '=', "$currentUserId")->get()->all());

                foreach ($articles as $article) {
                    if ($article->created_at < $currentArticleDate) {
                        return redirect(route('article', $article->title));
                    }
                }

                break;
            case self::NEXTARTICLE:

                $currentArticle = Article::where('title', '=', "$slug")->first();
                $currentArticleTimestamp = ($currentArticle->created_at->timestamp);
                $currentUserId = Auth::user()->id;
                $articles = Article::where('user_id', '=', "$currentUserId")->get();

                foreach ($articles as $article) {
                    if ($article->created_at->timestamp > $currentArticleTimestamp) {
                        return redirect(route('article', $article->title));
                    }
                }

                break;
        }

        return redirect(route('article', $slug));
    }

    public function deleteArticle($slug)
    {
        Article::where('title', $slug)->delete();

        return redirect(route('articles'));
    }
}
