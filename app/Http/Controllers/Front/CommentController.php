<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use http\Client\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function store(CommentRequest $commentRequest, Request $request)
    {
        dd($request->article_id);
        $user = Auth::user();
        $user->articles()->create([
            'body' => $commentRequest->body,
            'articleId' => ''
        ]);
    }
}
