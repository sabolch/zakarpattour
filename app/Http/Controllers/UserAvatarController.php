<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Image;
use Intervention\Image\Exception\NotReadableException;

class UserAvatarController extends Controller
{
    public function store(Request $request)
    {
        $path = Storage::putFileAs(
            'avatars', $request->file('avatar'), $request->input('user_id') . '.jpg'
        );
        return response()->json(['success' =>  true], 200);
    }

    public static function get($user_id,$size = null)
    {
        try {
            $img = Image::make(storage_path('app' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . $user_id . '.jpg'));
            if ($size){
                $size = explode('x',$size);
                $img->resize($size[0], $size[1]);
            }
            return $img->response();

        } catch (NotReadableException $exception) {
            return Image::make(storage_path('app' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . 'default.jpg'))->response();
        }

    }
}
