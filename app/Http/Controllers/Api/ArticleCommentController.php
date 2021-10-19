<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentApiRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Tymon\JWTAuth\Facades\JWTAuth;

class ArticleCommentController extends Controller
{
    public function index()
    {
        return CommentResource::collection(Comment::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CommentApiRequest  $request
     */
    public function store($id, CommentApiRequest $request)
    {
            $user = JWTAuth::parseToken()->authenticate();
            $user->comments()->create([
                'body' => $request->body,
                'article_id' => $id,
                'parent_id'  => $request->parent_id,
                'user_id'    => $user->id
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($articleId, $commentId)
    {
        $comment = new CommentResource(Comment::find($commentId));
        if ($comment){
            return $comment->toArray($articleId);
        } else {
            return response()->json([
                'message' => 'Comment not found'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CommentApiRequest  $request
     * @param  int  $id
     */
    public function update(CommentApiRequest $request, $id, $commentId)
    {
        $comment = (new CommentResource(Comment::where("id", '=', "$commentId")->first()));
        $user = JWTAuth::parseToken()->authenticate();
        if ($comment && ($comment->user_id == $user->id)) {
            $comment->resource->body = $request->body;
            $comment->resource->save();

            return $comment->toArray($id);
        } else {
            return response()->json([
                'message' => 'Comment not found'
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
        $comment = Comment::find($id);
        $user = JWTAuth::parseToken()->authenticate();
        if ($comment && ($comment->user_id == $user->id)) {
            $comment->delete();

            return response()->json([
                'message' => "Comment successfully deleted"
            ]);
        } else {
            return response()->json([
                'message' => 'Comment not found'
            ]);
        }
    }
    public function showAll($articleId) {
        return CommentResource::collection(Comment::where('article_id', '=', "$articleId")->paginate(20));
    }
}
