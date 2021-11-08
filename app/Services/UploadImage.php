<?php


namespace App\Services;


use App\Http\Requests\UploadImageRequest;

class UploadImage
{
    public static function uploadImage(UploadImageRequest $request)
    {
        return $request->image->store('public/images');
    }
}
