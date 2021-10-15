<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ArticleCommentController extends Controller
{
    private $commentValidationRules = [
        'body'      => 'required|max:300'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store($id, Request $request)
    {
        $response = [ 'response' => '', 'success' => false ];
        $validator = Validator::make($request->all(), $this->commentValidationRules);

        if ($validator->fails()) {
            $response['response'] = $validator->messages();
        }else{
            $user = JWTAuth::parseToken()->authenticate();
            $user->comments()->create([
                'body' => $request->body,
                'article_id' => $id,
                'parent_id'  => $request->parent_id,
                'user_id'    => $user->id
            ]);

            return response()->json([
                'message' => 'Comment Successfully created',
            ]);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($articleId, $commentId)
    {
        $comment = new CommentResource(Comment::findOrFail($commentId));
        return $comment->toArray($articleId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id, $commentId)
    {
        $response = [ 'response' => '', 'success' => false ];
        $validator = Validator::make($request->all(), $this->commentValidationRules);
        if ($validator->fails()) {
            $response['response'] = $validator->messages();
        }else{
            $comment = (new CommentResource(Comment::where("id", '=', "$commentId")->first()));
            $comment->resource->body = $request->body;
            $comment->resource->save();

            return $comment->toArray($id);
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
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => "Comment successfully deleted"
        ]);
    }
    public function showAll($articleId) {
        return CommentResource::collection(Comment::where('article_id', '=', "$articleId")->paginate(20));
    }
}
