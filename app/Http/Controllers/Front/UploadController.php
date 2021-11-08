<?php


namespace App\Http\Controllers\Front;

use App\Http\Requests\UploadImageRequest;
use App\Services\UploadImage;
use Illuminate\Support\Facades\Auth;

class UploadController
{
    public function uploadImage(UploadImageRequest $request)
    {

        Auth::user()->images()->create([
            'url' => UploadImage::uploadImage($request)
        ]);

        return back();
    }
}
