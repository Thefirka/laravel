<?php


namespace App\Services;


use App\Http\Requests\TagRequest;

class PrepareTags
{
    public static function prepareTags(TagRequest $tagRequest)
    {
        return explode( ',', preg_replace('/\s+/', '', $tagRequest->tags));
    }
}
