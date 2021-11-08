<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use mysql_xdevapi\XSession;

class DeleteArticlesWithoutComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'deleting a week-old articles without any comments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $articles = Article::all();

        foreach ($articles as $article) {
            if (
                $article->whereDate('created_at', '<=', now()->subDays(7)->startOfDay()->toDateTimeString())
                && !($article->comments()->exists())
            ) {
                $article->delete();
            }
        }

        return Command::SUCCESS;
    }
}
