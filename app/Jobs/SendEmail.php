<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }


    public function handle()
    {
        $article = Article::find($this->comment->article_id);
        $user = User::find($article->user_id);

        Mail::send(
            'emails/newCommentEmail',
            [ 'user' => $user, 'article' => $article ],
            function ($m) use ($user) {
                $m->from('testim.mailer@gmail.com', 'test');
                $m->to($user->email, $user->name)->subject('just a test');
            }
        );
    }
}
