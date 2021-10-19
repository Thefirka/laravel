<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleApiRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use http\Env\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ArticleController extends Controller
{

    public function index()
    {
        return ArticleResource::collection(Article::paginate(20));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  ArticleApiRequest  $request
     */
    public function store(ArticleApiRequest $request)
    {
            $user = JWTAuth::parseToken()->authenticate();
            $user->articles()->create([
                'title'     => $request->title,
                'body'      => $request->body,
            ]);

            return response()->json([
                'message' => 'Article Successfully created',
            ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show(Article $id)
    {
        return new ArticleResource(Article::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ArticleApiRequest  $request
     * @param  int  $id
     */
    public function update(ArticleApiRequest $request, $id)
    {
            $article = (new ArticleResource(Article::find($id)));
            $user = JWTAuth::parseToken()->authenticate();
            if ($article && ($article->user_id == $user->id)) {
                $article->resource->title = $request->title;
                $article->resource->body  = $request->body;
                $article->resource->save();
                return $article->toArray($request);
            } else {
                return response()->json([
                    'message' => 'Article not found'
                ]);
            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        $user = JWTAuth::parseToken()->authenticate();
        if ($article && ($article->user_id == $user->id)) {
            $article->delete();

            return response()->json([
                'message' => "article successfully deleted"
            ]);
        } else {
            return response()->json([
                'message' => 'article not found'
            ]);
        }
    }
}
