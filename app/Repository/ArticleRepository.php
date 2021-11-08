<?php


namespace App\Repository;

use App\Models\Article;

class ArticleRepository
{
    public function findArticlesByTag(array $tags)
    {
       return Article::join('article_tag', 'articles.id', '=', 'article_tag.article_id')
            ->join('tags', 'tags.id', '=', 'article_tag.tag_id')->whereIn('tags.name', $tags)->get();
    }
}
