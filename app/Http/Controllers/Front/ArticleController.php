<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\TagRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\OpenWeatherApi\CurrentWeather;
use App\Services\PrepareTags;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public const PREVIOUSARTICLE = 'My Previous Article';
    public const NEXTARTICLE     = 'My Next Article';

    public function allCurrentUserArticles()
    {
        $articles = Auth::user()->articles()->get();

        return view('currentUserArticles', ['articles' => $articles]);
    }

    public function allArticles()
    {
        $articles = Article::paginate(10);
        return view('home', [ 'articles' => $articles ]);
    }

    public function findArticlesByTag(TagRequest $tagRequest)
    {

        $tags = PrepareTags::prepareTags($tagRequest);
        $articles = [];

        foreach ($tags as $tag) {
            $tag = Tag::where('name', $tag)->with('articles')->first();
            if ($tag) {
                $articles[] = $tag->articles;
            }
        }
        $allArticles = new Collection();

            foreach ($articles as $articlesOfTag) {
               $allArticles = $allArticles->merge($articlesOfTag);
            }

        return view('articlesByTags', [ 'articles' => isset($allArticles) ? $allArticles : '']);
    }

    public function newArticle()
    {
        return view('newArticle');
    }

    public function createArticle(ArticleRequest $articleRequest, CategoryRequest $categoryRequest, TagRequest $tagRequest)
    {
        $weather = CurrentWeather::getWeather();

        $article = Auth::user()->articles()->create([
            'title'               => $articleRequest->title,
            'body'                => $articleRequest->body,
            'temperature'         => $weather['temperature'],
            'weather_description' => $weather['weather_description']
        ]);

        Category::firstOrCreate(
            ['name' => $categoryRequest->category],
            ['name' => $categoryRequest->category])->articles()->attach($article);

        $tags = PrepareTags::prepareTags($tagRequest);

        foreach ($tags as $tag) {
           Tag::firstOrCreate(
                ['name' => $tag],
                ['name' => $tag])->articles()->attach($article);
        }

        return redirect(route('home'));
    }

    public function showArticle($slug)
    {
        $article  = Article::where('slug', $slug)->with('categories')->firstOrFail();
        if ($article->comments()->exists()) {
            $article_id = $article->id;

            $comments = $article->comments()->get()->toTree();

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
        }

            return view('article', [
                'article' => $article, 'commentShow' => isset($commentShow) ? $commentShow : ''
            ]);
    }

    public function loadArticle($slug, Request $request)
    {
        $loadArticle = $request->post('LoadArticle');
        $currentArticle = Article::where('slug', '=', $slug)->first();
        $currentArticleDate = ($currentArticle->created_at);
        $article = Auth::user()->articles();

        switch ($loadArticle) {
            case self::PREVIOUSARTICLE:
                $article
                ->where('created_at', '<', $currentArticleDate)
                ->orderBy('created_at','DESC');
                break;
            case self::NEXTARTICLE:
                $article
                ->where('created_at', '>', $currentArticleDate)
                ->orderBy('created_at','ASC');
                break;
        }

        $article = $article->first();

        return  redirect(route('article', isset($article->slug) ? $article->slug : $slug));
    }

    public function deleteArticle($slug)
    {
        Article::where('slug', $slug)->delete();

        return redirect(route('articles'));
    }
}
