<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ArticleController extends Controller
{
    private $articleValidationRules = [
        'title'     => 'required|max:30|unique:articles,title',
        'body'      => 'required|max:300'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $response = [ 'response' => '', 'success' => false ];
        $validator = Validator::make($request->all(), $this->articleValidationRules);

        if ($validator->fails()) {
            $response['response'] = $validator->messages();
        }else{
            $user = JWTAuth::parseToken()->authenticate();
            $user->articles()->create([
                'title'     => $request->title,
                'body'      => $request->body,
            ]);

            return response()->json([
                'message' => 'Article Successfully created',
            ]);
        }

        return $response;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $article = new ArticleResource(Article::findOrFail($id));

        return $article->toArray($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $response = [ 'response' => '', 'success' => false ];
        $validator = Validator::make($request->all(), $this->articleValidationRules);

        if ($validator->fails()) {
            $response['response'] = $validator->messages();
        }else{
            $article = (new ArticleResource(Article::findOrFail($id)));
            $article->resource->title = $request->title;
            $article->resource->body  = $request->body;
            $article->resource->save();

            return $article->toArray($request);
            }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json([
            'message' => "article successfully deleted"
        ]);
    }

    public function allArticles() {
        return ArticleResource::collection(Article::paginate(20));
    }
}
