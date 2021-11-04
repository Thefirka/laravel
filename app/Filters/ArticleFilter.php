<?php


namespace App\Filters;

use App\Http\Requests\TagRequest;
use App\Repository\ArticleRepository;

class ArticleFilter extends QueryFilters
{
    protected $repository;

    public function __construct(ArticleRepository $repository, TagRequest $request)
    {
        $this->repository = $repository;
        parent::__construct($request);
    }

    public function tags($searchArray)
    {
        return $this->repository->findArticlesByTag($searchArray);
    }
}
