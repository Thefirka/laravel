<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentApiRequest;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;

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
    public function store(Article $article, CommentApiRequest $request)
    {
         $user = auth('api')->user();
         $user->comments()->create([
                 'body'       => $request->body,
                 'article_id' => $article->id,
                 'parent_id'  => $request->parent_id,
                 'user_id'    => $user->id
             ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show(Article $article, Comment $comment)
    {
         if ($comment->article_id == $article->id) {
             return new CommentResource($comment);
         } else {
             return response()->json([
                 'message' => 'Comment belongs to different article'
             ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CommentApiRequest  $request
     */
    public function update(CommentApiRequest $request, Article $article, Comment $comment)
    {
         if ($comment->article_id == $article->id) {
             $this->authorize('update', $comment);
             $comment->resource->body = $request->body;
             $comment->resource->save();

             return new CommentResource($comment);
         } else {
             return response()->json([
                'message' => 'Comment belongs to different article'
             ]);
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return response()->json([
                'message' => "Comment successfully deleted"
            ]);
    }
    public function showAll(Article $article)
    {
        return CommentResource::collection(Comment::where('article_id', '=', "$article->id")->paginate(20));
    }
}
