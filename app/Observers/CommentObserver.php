<?php

namespace App\Observers;

use App\Jobs\SendEmail;
use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        SendEmail::dispatch($comment);
    }
}
