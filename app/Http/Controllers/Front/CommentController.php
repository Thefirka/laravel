<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $commentRequest)
    {
        $user = Auth::user();

        $user->comments()->create([
            'body' => $commentRequest->body,
            'article_id' => $commentRequest->article_id,
            'parent_id'  => $commentRequest->parent_id
        ]);

        return back();
    }
}
