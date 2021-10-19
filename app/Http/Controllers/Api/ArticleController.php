<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleApiRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
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
     * @param  Article $articleResource
     */
    public function show(Article $articleResource)
    {
        return new ArticleResource(Article::find($articleResource->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ArticleApiRequest  $request
     * @param  Article $articleResource
     */
    public function update(ArticleApiRequest $request, Article $articleResource)
    {
            $user = JWTAuth::parseToken()->authenticate();
            if ($articleResource && ($articleResource->user_id == $user->id)) {
                $articleResource->title = $request->title;
                $articleResource->body  = $request->body;
                $articleResource->save();

                return new ArticleResource($articleResource);
            } else {
                return response()->json([
                    'message' => 'Article not found'
                ]);
            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Article $articleResource
     */
    public function destroy(Article $articleResource)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($articleResource && ($articleResource->user_id == $user->id)) {
            $articleResource->delete();

            return response()->json([
                'message' => "Article successfully deleted"
            ]);
        } else {
            return response()->json([
                'message' => 'Article not found'
            ]);
        }
    }
}
